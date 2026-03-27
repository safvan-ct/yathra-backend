<?php
namespace App\Services;

use App\Repositories\Interfaces\RouteNodeRepositoryInterface;
use App\Repositories\Interfaces\TransitRouteRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RouteNodeService
{
    public function __construct(
        protected RouteNodeRepositoryInterface $routeNodeRepository,
        protected TransitRouteRepositoryInterface $routeRepository,
        protected TransitRouteService $transitRouteService,
        protected ActivityLogService $activityLogService
    ) {}

    public function list(int $routeId)
    {
        return $this->routeNodeRepository->listByRoute($routeId);
    }

    public function create(int $routeId, array $data)
    {
        $route = $this->routeRepository->find($routeId);
        if (! $route) {
            return null;
        }

        return DB::transaction(function () use ($routeId, $route, $data) {
            $nodes = $this->routeNodeRepository->listByRoute($routeId)->map(function ($node) {
                return [
                    'id'                   => $node->id,
                    'station_id'           => $node->station_id,
                    'stop_sequence'        => $node->stop_sequence,
                    'distance_from_origin' => $node->distance_from_origin,
                    'is_active'            => $node->is_active,
                ];
            })->toArray();

            $newSequence = (int) $data['stop_sequence'];

            foreach ($nodes as &$node) {
                if ($node['stop_sequence'] >= $newSequence) {
                    $node['stop_sequence']++;
                }
            }
            unset($node);

            $nodes[] = [
                'id'                   => null,
                'station_id'           => $data['station_id'],
                'stop_sequence'        => $data['stop_sequence'],
                'distance_from_origin' => $data['distance_from_origin'],
                'is_active'            => $data['is_active'] ?? true,
            ];

            $this->transitRouteService->syncNodes($routeId, $route->origin_id, $route->destination_id, $nodes);
            $this->activityLogService->log('route_node_created', 'App\\Models\\RouteNode', $routeId, ['route_id' => $routeId]);

            return $this->routeNodeRepository->listByRoute($routeId);
        });
    }

    public function update(int $routeId, int $nodeId, array $data)
    {
        $route = $this->routeRepository->find($routeId);
        if (! $route) {
            return null;
        }

        $target = $this->routeNodeRepository->findInRoute($routeId, $nodeId);
        if (! $target) {
            return false;
        }

        return DB::transaction(function () use ($routeId, $route, $nodeId, $data, $target) {
            $oldSequence = $target->stop_sequence;
            $newSequence = (int) ($data['stop_sequence'] ?? $oldSequence);

            $nodes = $this->routeNodeRepository->listByRoute($routeId)->map(function ($node) {
                return [
                    'id'                   => $node->id,
                    'station_id'           => $node->station_id,
                    'stop_sequence'        => $node->stop_sequence,
                    'distance_from_origin' => $node->distance_from_origin,
                    'is_active'            => $node->is_active,
                ];
            })->toArray();

            foreach ($nodes as &$node) {
                if ($node['id'] === $nodeId) {
                    $node['stop_sequence']        = $newSequence;
                    $node['station_id']           = $data['station_id'] ?? $node['station_id'];
                    $node['distance_from_origin'] = $data['distance_from_origin'] ?? $node['distance_from_origin'];
                    $node['is_active']            = $data['is_active'] ?? $node['is_active'];
                    continue;
                }

                if ($newSequence < $oldSequence) {
                    if ($node['stop_sequence'] >= $newSequence && $node['stop_sequence'] < $oldSequence) {
                        $node['stop_sequence']++;
                    }
                }

                if ($newSequence > $oldSequence) {
                    if ($node['stop_sequence'] <= $newSequence && $node['stop_sequence'] > $oldSequence) {
                        $node['stop_sequence']--;
                    }
                }
            }
            unset($node);

            $this->transitRouteService->syncNodes($routeId, $route->origin_id, $route->destination_id, $nodes);
            $this->activityLogService->log('route_node_updated', 'App\\Models\\RouteNode', $nodeId, ['route_id' => $routeId]);

            return $this->routeNodeRepository->listByRoute($routeId);
        });
    }

    public function delete(int $routeId, int $nodeId)
    {
        $route = $this->routeRepository->find($routeId);
        if (! $route) {
            return null;
        }

        $target = $this->routeNodeRepository->findInRoute($routeId, $nodeId);
        if (! $target) {
            return false;
        }

        return DB::transaction(function () use ($routeId, $route, $nodeId) {
            $nodes = $this->routeNodeRepository->listByRoute($routeId)
                ->reject(fn($node) => $node->id === $nodeId)
                ->values()
                ->map(function ($node, $index) {
                    return [
                        'id'                   => $node->id,
                        'station_id'           => $node->station_id,
                        'stop_sequence'        => $index + 1,
                        'distance_from_origin' => $node->distance_from_origin,
                        'is_active'            => $node->is_active,
                    ];
                })
                ->toArray();

            if (count($nodes) < 2) {
                throw ValidationException::withMessages(['nodes' => ['A route must contain at least two nodes.']]);
            }

            $this->transitRouteService->syncNodes($routeId, $route->origin_id, $route->destination_id, $nodes);
            $this->activityLogService->log('route_node_deleted', 'App\\Models\\RouteNode', $nodeId, ['route_id' => $routeId]);

            return true;
        });
    }
}
