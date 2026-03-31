<?php
namespace App\Services;

use App\Enums\SuggestionStatus;
use App\Enums\SuggestionType;
use App\Models\Operator;
use App\Models\Suggestion;
use App\Repositories\Interfaces\SuggestionRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class SuggestionService
{
    public function __construct(
        protected SuggestionRepositoryInterface $suggestionRepository,
        protected RewardService $rewardService,
        protected TrustService $trustService
    ) {}

    public function submitSuggestion(array $data): Suggestion
    {
        $user = \App\Models\User::find($data['user_id']);

        $data['suggestable_type'] = $data['suggestable_type'] == 'Stop' ? 'RouteNode' : $data['suggestable_type'];
        $modelClass               = $data['suggestable_type'] ? $this->getModelClass($data['suggestable_type']) : null;

        $data['type ']        = SuggestionType::NewEntry->value;
        $data['proposed_for'] =
        $data['suggestable_type'] === 'RouteNode' ? 'Route Stop' : ($data['suggestable_type'] === 'Station' ? 'Stop' : $data['suggestable_type']);
        $data['suggestable_type'] = $modelClass;

        $safeguardedEntities = [\App\Models\TransitRoute::class, \App\Models\RouteNode::class];
        // $isAutoApprovable    = $user && $user->trust_level === 'high' && ! in_array($modelClass, $safeguardedEntities);
        $isAutoApprovable = false;

        $data['status'] = $isAutoApprovable ? SuggestionStatus::Approved->value : SuggestionStatus::Pending->value;
        $exists         = false;

        if ($data['proposed_for'] === 'Bus') {
            $busService                               = app(BusService::class);
            $data['proposed_data']['bus_number_code'] = $busService->normalizeBusNumber($data['proposed_data']['bus_number'] ?? '');
            $data['proposed_data']['bus_number']      = $busService->formatBusNumber($data['proposed_data']['bus_number'] ?? '');
            $data['proposed_data']['operator_id']     = Operator::where('type', $data['proposed_data']['operator_type'])->first()?->id;

            $exists = Suggestion::where('proposed_data->bus_number', $data['proposed_data']['bus_number'])->exists();
        } elseif ($data['proposed_for'] === 'Stop') {
            $data['proposed_data']['code'] = strtoupper(str_replace(' ', '_', $data['proposed_data']['name'] ?? ''));

            $exists = Suggestion::where('proposed_data->name', $data['proposed_data']['name'])
                ->where('proposed_data->city_id', $data['proposed_data']['city_id'])->exists();
        } elseif ($data['proposed_for'] === 'Route') {
            $origin      = \App\Models\Station::find($data['proposed_data']['origin_id']);
            $destination = \App\Models\Station::find($data['proposed_data']['destination_id']);

            $data['proposed_data']['origin_name']      = $origin->name;
            $data['proposed_data']['destination_name'] = $destination->name;

            $exists = Suggestion::where('proposed_data->origin_id', $data['proposed_data']['origin_id'])
                ->where('proposed_data->destination_id', $data['proposed_data']['destination_id'])
                ->where('proposed_data->path_signature', $data['proposed_data']['path_signature'])
                ->exists();
        } elseif ($data['proposed_for'] === 'Trip') {
            $bus   = \App\Models\Bus::find($data['proposed_data']['bus_id']);
            $route = \App\Models\TransitRoute::with(['origin' => fn($q) => $q->select('id', 'name'), 'destination' => fn($q) => $q->select('id', 'name')])->find($data['proposed_data']['route_id']);

            $routeName = "{$route->origin->name} - {$route->destination->name} ({$route->path_signature})";

            $data['proposed_data']['bus_name']   = "{$bus->bus_name} - {$bus->bus_number}";
            $data['proposed_data']['route_name'] = $routeName;

            $exists = Suggestion::where('proposed_data->bus_id', $data['proposed_data']['bus_id'])
                ->where('proposed_data->route_id', $data['proposed_data']['route_id'])
                ->where('proposed_data->departure_time', $data['proposed_data']['departure_time'])
                ->where('proposed_data->arrival_time', $data['proposed_data']['arrival_time'])
                ->exists();
        } elseif ($data['proposed_for'] === 'Route Stop') {
            $route     = \App\Models\TransitRoute::find($data['proposed_data']['route_id']);
            $routeStop = \App\Models\RouteNode::with(['station' => fn($q) => $q->select('id', 'name')])->find($data['proposed_data']['before_node_id']);
            $station   = \App\Models\Station::find($data['proposed_data']['station_id']);

            $routeName     = "{$route->origin->name} - {$route->destination->name} ({$route->path_signature})";
            $routeStopName = $routeStop ? "{$routeStop->station->name}, (Sequence: {$routeStop->stop_sequence}), (Distance:                 {$routeStop->distance_from_origin}Km)" : 'N/A';
            $stopName      = "{$station->name} ({$data['proposed_data']['distance_from_origin']}Km)";

            $data['proposed_data']['route_name']      = $routeName;
            $data['proposed_data']['route_stop_name'] = $routeStopName;
            $data['proposed_data']['stop_name']       = $stopName;
            $data['proposed_data']['stop_sequence']   = $routeStop->stop_sequence + 1;

            $exists = Suggestion::where('proposed_data->route_id', $data['proposed_data']['route_id'])
                ->where('proposed_data->station_id', $data['proposed_data']['station_id'])
                ->exists();
        }

        if ($exists) {
            throw ValidationException::withMessages(['proposed_data' => ['An identical suggestion already exists.']]);
        }

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
            throw $e;
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
