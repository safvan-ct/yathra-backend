<?php
namespace App\Repositories\Eloquent;

use App\Models\TransitRoute;
use App\Repositories\Interfaces\TransitRouteRepositoryInterface;

class TransitRouteRepository implements TransitRouteRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        $query = TransitRoute::select(['id', 'origin_id', 'destination_id', 'path_signature', 'distance'])
        ->with(['origin' => fn($q) => $q->select('id', 'name'), 'destination' => fn($q) => $q->select('id', 'name')]);

        if (! empty($filters['origin_id'])) {
            $query->where('origin_id', $filters['origin_id']);
        }

        if (! empty($filters['destination_id'])) {
            $query->where('destination_id', $filters['destination_id']);
        }

        if (! empty($filters['variant'])) {
            $query->where('path_signature', $filters['variant']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        if (! empty($filters['search'])) {
            $searchTerm = trim($filters['search']);

            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('origin', function ($q2) use ($searchTerm) {
                    $q2->where('name', 'like', "%$searchTerm%");
                })->orWhereHas('destination', function ($q3) use ($searchTerm) {
                    $q3->where('name', 'like', "%$searchTerm%");
                })->orWhere('route_code', 'like', "%$searchTerm%");
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function find(int $id)
    {
        return TransitRoute::with(['origin', 'destination', 'nodes.station'])->find($id);
    }

    public function create(array $data)
    {
        return TransitRoute::create($data);
    }

    public function update(int $id, array $data)
    {
        $route = TransitRoute::find($id);
        if (! $route) {
            return null;
        }

        $route->update($data);
        return $route->fresh(['origin', 'destination', 'nodes.station']);
    }

    public function delete(int $id)
    {
        $route = TransitRoute::find($id);
        if (! $route) {
            return false;
        }

        return $route->delete();
    }

    public function findByOriginDestinationAndSignature(int $originId, int $destinationId, string $pathSignature, ?int $excludeId = null)
    {
        $query = TransitRoute::query()
            ->where('origin_id', $originId)
            ->where('destination_id', $destinationId)
            ->where('path_signature', $pathSignature);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->first();
    }
}
