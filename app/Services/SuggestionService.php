<?php
namespace App\Services;

use App\Enums\RewardActivityType;
use App\Enums\SuggestionStatus;
use App\Enums\SuggestionType;
use App\Models\Bus;
use App\Models\Operator;
use App\Models\RouteNode;
use App\Models\Station;
use App\Models\Suggestion;
use App\Models\TransitRoute;
use App\Models\Trip;
use App\Models\User;
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
        $user = User::find($data['user_id']);

        if (! $user || ! ($user instanceof User)) {
            throw new UnauthorizedException('Unauthorized');
        }

        $data['suggestable_type'] = $data['suggestable_type'] == 'Stop' ? 'RouteNode' : $data['suggestable_type'];
        $modelClass               = $data['suggestable_type'] ? $this->getModelClass($data['suggestable_type']) : null;

        $data['type ']            = SuggestionType::NewEntry->value;
        $data['proposed_for']     = $data['suggestable_type'] === 'RouteNode' ? 'Route Stop' : ($data['suggestable_type'] === 'Station' ? 'Stop' : $data['suggestable_type']);
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
                ->where('proposed_data->city_id', $data['proposed_data']['city_id'])
                ->exists();
        } elseif ($data['proposed_for'] === 'Route') {
            $origin      = Station::find($data['proposed_data']['origin_id']);
            $destination = Station::find($data['proposed_data']['destination_id']);

            $data['proposed_data']['origin_name']      = $origin->name;
            $data['proposed_data']['destination_name'] = $destination->name;

            $exists = Suggestion::where('proposed_data->origin_id', $data['proposed_data']['origin_id'])
                ->where('proposed_data->destination_id', $data['proposed_data']['destination_id'])
                ->where('proposed_data->path_signature', $data['proposed_data']['path_signature'])
                ->exists();
        } elseif ($data['proposed_for'] === 'Trip') {
            $bus   = Bus::find($data['proposed_data']['bus_id']);
            $route = TransitRoute::with([
                'origin'      => fn($q)      => $q->select('id', 'name'),
                'destination' => fn($q) => $q->select('id', 'name'),
            ])->find($data['proposed_data']['route_id']);

            $routeName = "{$route->origin->name} - {$route->destination->name} ({$route->path_signature})";

            $data['proposed_data']['bus_name']   = "{$bus->bus_name} - {$bus->bus_number}";
            $data['proposed_data']['route_name'] = $routeName;

            $exists = Suggestion::where('proposed_data->bus_id', $data['proposed_data']['bus_id'])
                ->where('proposed_data->route_id', $data['proposed_data']['route_id'])
                ->where('proposed_data->departure_time', $data['proposed_data']['departure_time'])
                ->where('proposed_data->arrival_time', $data['proposed_data']['arrival_time'])
                ->exists();
        } elseif ($data['proposed_for'] === 'Route Stop') {
            $route     = TransitRoute::find($data['proposed_data']['route_id']);
            $routeStop = RouteNode::with(['station' => fn($q) => $q->select('id', 'name')])->find($data['proposed_data']['before_node_id']);
            $station   = Station::find($data['proposed_data']['station_id']);

            $routeName     = "{$route->origin->name} - {$route->destination->name} ({$route->path_signature})";
            $routeStopName = $routeStop ? "{$routeStop->station->name}, (Sequence: {$routeStop->stop_sequence}), (Distance:                 {$routeStop->distance_from_origin}Km)" : 'N/A';
            $stopName      = "{$station->name} ({$data['proposed_data']['distance_from_origin']}Km)";

            $data['proposed_data']['route_name']      = $routeName;
            $data['proposed_data']['route_stop_name'] = $routeStopName;
            $data['proposed_data']['stop_name']       = $stopName;
            $data['proposed_data']['stop_sequence']   = $routeStop->stop_sequence + 1;

            // KM & Sequence Validation
            $existingNodes    = RouteNode::where('route_id', $route->id)->orderBy('stop_sequence')->get();
            $newNodes         = [];
            $proposedSequence = $data['proposed_data']['stop_sequence'];

            foreach ($existingNodes as $node) {
                $newNodes[] = [
                    'station_id'           => $node->station_id,
                    'stop_sequence'        => $node->stop_sequence >= $proposedSequence ? $node->stop_sequence + 1 : $node->stop_sequence,
                    'distance_from_origin' => $node->distance_from_origin,
                    'is_active'            => $node->is_active,
                ];
            }
            $newNodes[] = [
                'station_id'           => $data['proposed_data']['station_id'],
                'stop_sequence'        => $proposedSequence,
                'distance_from_origin' => $data['proposed_data']['distance_from_origin'],
                'is_active'            => true,
            ];

            app(TransitRouteService::class)->validateNodePayload($route->origin_id, $route->destination_id, $newNodes);

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

        if ($typeEnum === SuggestionType::NewEntry) {
            if ($modelClass === TransitRoute::class) {
                $routeData            = $suggestion->proposed_data;
                $routeData['variant'] = $routeData['path_signature'] ?? 'DIRECT';
                $routeData['nodes']   = $routeData['nodes'] ?? [];

                if (empty($routeData['nodes'])) {
                    $routeData['nodes'] = [
                        [
                            'station_id'           => $routeData['origin_id'],
                            'stop_sequence'        => 1,
                            'distance_from_origin' => 0,
                            'is_active'            => true,
                        ],
                        [
                            'station_id'           => $routeData['destination_id'],
                            'stop_sequence'        => 2,
                            'distance_from_origin' => $routeData['distance'] ?? 0,
                            'is_active'            => true,
                        ],
                    ];
                }

                app(TransitRouteService::class)->create($routeData);
            } elseif ($modelClass === RouteNode::class) {
                $nodeData = $suggestion->proposed_data;

                // Shift subsequent nodes to make room for the new one
                RouteNode::where('route_id', (int) $nodeData['route_id'])
                    ->where('stop_sequence', '>=', (int) $nodeData['stop_sequence'])
                    ->increment('stop_sequence');

                RouteNode::create([
                    'route_id'             => (int) $nodeData['route_id'],
                    'station_id'           => (int) $nodeData['station_id'],
                    'stop_sequence'        => (int) $nodeData['stop_sequence'],
                    'distance_from_origin' => $nodeData['distance_from_origin'],
                    'is_active'            => true,
                ]);
            } elseif ($modelClass === Trip::class) {
                app(TripService::class)->create($suggestion->proposed_data);
            } else {
                $modelClass::create($suggestion->proposed_data);
            }
            $activityType = RewardActivityType::NewEntry;
        } elseif ($typeEnum === SuggestionType::Update) {
            if ($modelClass === TransitRoute::class) {
                $routeData            = $suggestion->proposed_data;
                $routeData['variant'] = $routeData['path_signature'] ?? 'DIRECT';

                app(TransitRouteService::class)->update($suggestion->suggestable_id, $routeData);
            } else {
                $modelClass::where('id', $suggestion->suggestable_id)->update($suggestion->proposed_data);
            }
            $activityType = RewardActivityType::Update;
        } else {
            $activityType = RewardActivityType::Verification;
        }

        $this->rewardService->rewardUser($suggestion->user_id, $suggestion->id, $activityType);
        $this->trustService->onSuggestionApproved($suggestion->user, $typeEnum);
    }

    protected function getModelClass(string $type): ?string
    {
        $map = [
            'Trip'      => Trip::class,
            'Route'     => TransitRoute::class,
            'Station'   => Station::class,
            'Bus'       => Bus::class,
            'RouteNode' => RouteNode::class,
        ];

        return $map[$type] ?? null;
    }

    public function validateUser(Request $request)
    {
        $user = $request->user();
        if (! $user || ! ($user instanceof User)) {
            throw new UnauthorizedException('Unauthorized');
        }
    }
}
