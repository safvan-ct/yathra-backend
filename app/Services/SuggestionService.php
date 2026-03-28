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
        protected RewardService $rewardService
    ) {}

    public function submitSuggestion(array $data): Suggestion
    {
        $data['status']           = SuggestionStatus::Pending->value;
        $data['suggestable_type'] = $data['suggestable_type'] ? $this->getModelClass($data['suggestable_type']) : null;
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

        $isNewEntry = is_null($suggestion->suggestable_id);

        if ($isNewEntry) {
            $modelClass::create($suggestion->proposed_data);
            $activityType = \App\Enums\RewardActivityType::NewEntry;
        } else {
            $modelClass::where('id', $suggestion->suggestable_id)->update($suggestion->proposed_data);
            $activityType = \App\Enums\RewardActivityType::Verification;
        }

        $this->rewardService->rewardUser($suggestion->user_id, $suggestion->id, $activityType);
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
