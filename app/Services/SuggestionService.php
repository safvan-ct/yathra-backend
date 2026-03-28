<?php
namespace App\Services;

use App\Enums\SuggestionStatus;
use App\Models\Suggestion;
use App\Repositories\Interfaces\SuggestionRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;

class SuggestionService
{
    public function __construct(
        protected SuggestionRepositoryInterface $suggestionRepository,
        protected RewardService $rewardService,
        protected TrustService $trustService
    ) {}

    public function submitSuggestion(array $data): Suggestion
    {
        $user                     = \App\Models\User::find($data['user_id']);
        $modelClass               = $data['suggestable_type'] ? $this->getModelClass($data['suggestable_type']) : null;
        $data['suggestable_type'] = $modelClass;

        $safeguardedEntities = [\App\Models\TransitRoute::class, \App\Models\RouteNode::class];
        $isAutoApprovable    = $user && $user->trust_level === 'high' && ! in_array($modelClass, $safeguardedEntities);

        $data['status'] = $isAutoApprovable ? SuggestionStatus::Approved->value : SuggestionStatus::Pending->value;

        if ($isAutoApprovable) {
            DB::beginTransaction();
            try {
                $suggestion = $this->suggestionRepository->create($data);
                $this->handleApproval($suggestion);
                DB::commit();
                return $suggestion;
            } catch (Exception $e) {
                DB::rollBack();
                throw clone $e;
            }
        }

        return $this->suggestionRepository->create($data);
    }

    public function reviewSuggestion(int $suggestionId, SuggestionStatus $status, int $adminId, ?string $reviewNote = null): Suggestion
    {
        $suggestion = $this->suggestionRepository->find($suggestionId);
        if (! $suggestion) {
            throw new Exception("Suggestion not found.");
        }

        if ($suggestion->status !== SuggestionStatus::Pending) {
            throw new Exception("Suggestion is already processed.");
        }

        DB::beginTransaction();
        try {
            $this->suggestionRepository->update($suggestion->id, ['status' => $status->value, 'admin_id' => $adminId, 'review_note' => $reviewNote]);
            $suggestion->refresh();

            if ($status === SuggestionStatus::Approved) {
                $this->handleApproval($suggestion);
            } elseif ($status === SuggestionStatus::Rejected) {
                $this->trustService->onSuggestionRejected($suggestion->user);
            } elseif ($status === SuggestionStatus::Flagged) {
                $this->trustService->onSuggestionFlagged($suggestion->user);
            }

            DB::commit();

            return $suggestion;
        } catch (Exception $e) {
            DB::rollBack();
            throw clone $e;
        }
    }

    protected function handleApproval(Suggestion $suggestion): void
    {
        $modelClass = $suggestion->suggestable_type;
        if (! $suggestion->suggestable_type) {
            throw new Exception("Invalid suggestable type.");
        }

        $typeEnum = $suggestion->type; // App\Enums\SuggestionType

        if ($typeEnum === \App\Enums\SuggestionType::NewEntry) {
            $modelClass::create($suggestion->proposed_data);
            $activityType = \App\Enums\RewardActivityType::NewEntry;
        } elseif ($typeEnum === \App\Enums\SuggestionType::Update) {
            $modelClass::where('id', $suggestion->suggestable_id)->update($suggestion->proposed_data);
            $activityType = \App\Enums\RewardActivityType::Update;
        } else {
            // Verification doesn't mutate existing core data
            $activityType = \App\Enums\RewardActivityType::Verification;
        }

        $this->rewardService->rewardUser($suggestion->user_id, $suggestion->id, $activityType);
        $this->trustService->onSuggestionApproved($suggestion->user, $typeEnum);
    }

    protected function getModelClass(string $type): ?string
    {
        $map = [
            'Trip'      => \App\Models\Trip::class,
            'Route'     => \App\Models\TransitRoute::class,
            'Station'   => \App\Models\Station::class,
            'Bus'       => \App\Models\Bus::class,
            'RouteNode' => \App\Models\RouteNode::class,
        ];

        return $map[$type] ?? null;
    }

    public function validateUser(Request $request)
    {
        $user = $request->user();
        if (! $user || ! ($user instanceof \App\Models\User)) {
            throw new UnauthorizedException('Unauthorized');
        }
    }
}
