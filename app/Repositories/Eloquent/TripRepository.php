<?php
namespace App\Repositories\Eloquent;

use App\Models\Trip;
use App\Repositories\Interfaces\TripRepositoryInterface;

class TripRepository implements TripRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        $query = Trip::query()->with(['bus.operator', 'route.origin', 'route.destination']);

        if (! empty($filters['route_id'])) {
            $query->where('route_id', $filters['route_id']);
        }

        if (! empty($filters['bus_id'])) {
            $query->where('bus_id', $filters['bus_id']);
        }

        if (! empty($filters['operator_id'])) {
            $query->whereHas('bus', fn($q) => $q->where('operator_id', $filters['operator_id']));
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['active_only']) && (bool) $filters['active_only']) {
            $query->where('status', 'Active');
        }

        if (isset($filters['day_index'])) {
            $dayIndex = (int) $filters['day_index'];
            $query->whereRaw("JSON_EXTRACT(days_of_week, '$[$dayIndex]') = true");
        }

        return $query->orderBy('departure_time')->paginate($perPage);
    }

    public function find(int $id)
    {
        return Trip::with(['bus.operator', 'route.origin', 'route.destination'])->find($id);
    }

    public function create(array $data)
    {
        return Trip::create($data);
    }

    public function update(int $id, array $data)
    {
        /** @var \App\Models\Trip|null $trip */
        $trip = Trip::find($id);
        if (! $trip) {
            return null;
        }

        $trip->update($data);
        return $trip->fresh(['bus.operator', 'route.origin', 'route.destination']);
    }

    public function delete(int $id)
    {
        /** @var \App\Models\Trip|null $trip */
        $trip = Trip::find($id);
        if (! $trip) {
            return false;
        }

        return $trip->delete();
    }

    public function existsDuplicate(int $busId, int $routeId, string $departureTime, ?int $excludeId = null): bool
    {
        $query = Trip::query()
            ->where('bus_id', $busId)
            ->where('route_id', $routeId)
            ->where('departure_time', $departureTime)
            ->whereNull('deleted_at');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function hasOverlap(int $busId, string $departureTime, string $arrivalTime, ?int $excludeId = null): bool
    {
        $query = Trip::query()
            ->where('bus_id', $busId)
            ->whereNull('deleted_at')
            ->whereIn('status', ['Active', 'Delayed'])
            ->whereRaw('? < arrival_time AND ? > departure_time', [$departureTime, $arrivalTime]);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
