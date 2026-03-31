<?php
namespace App\Services;

use App\Models\Station;
use App\Repositories\Interfaces\RouteNodeRepositoryInterface;
use App\Repositories\Interfaces\TransitRouteRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransitRouteService
{
    public function __construct(
        protected TransitRouteRepositoryInterface $routeRepository,
        protected RouteNodeRepositoryInterface $routeNodeRepository,
        protected ActivityLogService $activityLogService
    ) {}

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->routeRepository->paginate($filters, $perPage);
    }

    public function get(int $id)
    {
        return $this->routeRepository->find($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $pathSignature = $this->normalizeSignature($data['variant']);
            $this->assertUniqueVariant($data['origin_id'], $data['destination_id'], $pathSignature);

            $routeCode = $this->buildRouteCode($data['origin_id'], $data['destination_id'], $pathSignature);

            $route = $this->routeRepository->create([
                'origin_id'      => $data['origin_id'],
                'destination_id' => $data['destination_id'],
                'path_signature' => $pathSignature,
                'route_code'     => $routeCode,
                'distance'       => $data['distance'] ?? 0,
                'is_active'      => $data['is_active'] ?? true,
            ]);

            $this->syncNodes($route->id, $data['origin_id'], $data['destination_id'], $data['nodes']);
            $updatedRoute = $this->routeRepository->find($route->id);

            $this->activityLogService->log('route_created', 'App\\Models\\TransitRoute', $route->id, $updatedRoute->toArray());

            return $updatedRoute;
        });
    }

    public function update(int $id, array $data)
    {
        $existingRoute = $this->routeRepository->find($id);
        if (! $existingRoute) {
            return null;
        }

        return DB::transaction(function () use ($id, $data, $existingRoute) {
            $originId      = $data['origin_id'] ?? $existingRoute->origin_id;
            $destinationId = $data['destination_id'] ?? $existingRoute->destination_id;
            $pathSignature = $this->normalizeSignature($data['variant'] ?? $existingRoute->path_signature);

            $this->assertUniqueVariant($originId, $destinationId, $pathSignature, $id);
            $routeCode = $this->buildRouteCode($originId, $destinationId, $pathSignature);

            $payload = [
                'origin_id'      => $originId,
                'destination_id' => $destinationId,
                'path_signature' => $pathSignature,
                'route_code'     => $routeCode,
            ];

            if (array_key_exists('distance', $data)) {
                $payload['distance'] = $data['distance'];
            }

            if (array_key_exists('is_active', $data)) {
                $payload['is_active'] = $data['is_active'];
            }

            $route = $this->routeRepository->update($id, $payload);

            $this->activityLogService->log('route_updated', 'App\\Models\\TransitRoute', $id, $route?->toArray());

            return $route;
        });
    }

    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {
            $deleted = $this->routeRepository->delete($id);
            if ($deleted) {
                $this->activityLogService->log('route_deleted', 'App\\Models\\TransitRoute', $id, null);
            }

            return $deleted;
        });
    }

    public function syncNodes(int $routeId, int $originId, int $destinationId, array $nodes): void
    {
        $this->validateNodePayload($originId, $destinationId, $nodes);

        DB::transaction(function () use ($routeId, $nodes) {
            $now = now();

            $prepared = collect($nodes)->map(function ($node) use ($routeId, $now) {
                return [
                    'id'                   => $node['id'] ?? null,
                    'route_id'             => $routeId,
                    'station_id'           => $node['station_id'],
                    'stop_sequence'        => $node['stop_sequence'],
                    'distance_from_origin' => $node['distance_from_origin'],
                    'is_active'            => $node['is_active'] ?? true,
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ];
            })->toArray();

            $incomingIds = collect($prepared)->pluck('id')->filter()->values()->toArray();

            $this->routeNodeRepository->shiftAllsequence($routeId);

            $this->routeNodeRepository->deleteRemovedNodesByRoute($routeId, $incomingIds);

            $this->routeNodeRepository->upsertBatch($prepared);
        });
    }

    public function validateNodePayload(int $originId, int $destinationId, array $nodes): void
    {
        if (count($nodes) < 2) {
            throw ValidationException::withMessages(['nodes' => ['A route must contain at least two nodes.']]);
        }

        usort($nodes, fn($a, $b) => $a['stop_sequence'] <=> $b['stop_sequence']);

        $expected         = 1;
        $seenStations     = [];
        $previousDistance = null;

        foreach ($nodes as $index => $node) {
            if ((int) $node['stop_sequence'] !== $expected) {
                throw ValidationException::withMessages(['nodes' => ['stop_sequence must be continuous starting from 1.']]);
            }

            if (isset($seenStations[$node['station_id']])) {
                throw ValidationException::withMessages(['nodes' => ['Duplicate station_id is not allowed within a route.']]);
            }

            $distance = (float) $node['distance_from_origin'];
            if ($distance < 0) {
                throw ValidationException::withMessages(['nodes' => ['distance_from_origin must be greater than or equal to 0.']]);
            }

            if ($previousDistance !== null && $distance <= $previousDistance) {
                throw ValidationException::withMessages(['nodes' => ['distance_from_origin must be strictly increasing.']]);
            }

            if ($index === 0 && (int) $node['station_id'] !== $originId) {
                throw ValidationException::withMessages(['nodes' => ['First node station_id must match origin_id.']]);
            }

            if ($index === count($nodes) - 1 && (int) $node['station_id'] !== $destinationId) {
                throw ValidationException::withMessages(['nodes' => ['Last node station_id must match destination_id.']]);
            }

            $seenStations[$node['station_id']] = true;
            $previousDistance                  = $distance;
            $expected++;
        }
    }

    protected function assertUniqueVariant(int $originId, int $destinationId, string $pathSignature, ?int $excludeId = null): void
    {
        $existing = $this->routeRepository->findByOriginDestinationAndSignature($originId, $destinationId, $pathSignature, $excludeId);
        if ($existing) {
            throw ValidationException::withMessages(['variant' => ['Route variant already exists for this origin and destination.']]);
        }
    }

    protected function buildRouteCode(int $originId, int $destinationId, string $pathSignature): string
    {
        $origin      = Station::find($originId);
        $destination = Station::find($destinationId);

        if (! $origin || ! $destination) {
            throw ValidationException::withMessages(['origin_id' => ['Origin and destination stations must exist.']]);
        }

        return strtoupper($origin->code . '_' . $destination->code . '_' . $pathSignature);
    }

    public function normalizeSignature(string $value): string
    {
        $normalized = strtoupper(trim($value));
        $normalized = preg_replace('/[^A-Z0-9_]+/', '_', $normalized) ?? $normalized;
        $normalized = preg_replace('/_+/', '_', $normalized) ?? $normalized;
        return trim($normalized, '_');
    }
}
