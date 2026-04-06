<?php
namespace App\Services;

use App\Models\Bus;
use App\Models\TransitRoute;
use App\Repositories\Interfaces\TripRepositoryInterface;
use Illuminate\Validation\ValidationException;

class TripService
{
    public function __construct(
        protected TripRepositoryInterface $tripRepository,
        protected ActivityLogService $activityLogService
    ) {}

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->tripRepository->paginate($filters, $perPage);
    }

    public function get(int $id)
    {
        return $this->tripRepository->find($id);
    }

    public function create(array $data)
    {
        $this->validateTripInputs($data);

        $trip = $this->tripRepository->create($data);
        // if ($trip) {
        //     $this->activityLogService->log('trip_created', 'App\\Models\\Trip', $trip->id, $trip->toArray());
        // }
        return $trip;
    }

    public function update(int $id, array $data)
    {
        $existing = $this->tripRepository->find($id);
        if (! $existing) {
            return null;
        }

        $merged = array_merge($existing->toArray(), $data);
        $this->validateTripInputs($merged, $id);

        $trip = $this->tripRepository->update($id, $data);
        // if ($trip) {
        //     $this->activityLogService->log('trip_updated', 'App\\Models\\Trip', $id, $trip->toArray());
        // }

        return $trip;
    }

    public function delete(int $id)
    {
        $deleted = $this->tripRepository->delete($id);
        // if ($deleted) {
        //     $this->activityLogService->log('trip_deleted', 'App\\Models\\Trip', $id, null);
        // }

        return $deleted;
    }

    public function getByDayIndex(int $dayIndex, array $filters = [], int $perPage = 15)
    {
        if ($dayIndex < 0 || $dayIndex > 6) {
            throw ValidationException::withMessages(['day_index' => ['day_index must be between 0 and 6.']]);
        }

        $filters['day_index'] = $dayIndex;
        return $this->tripRepository->paginate($filters, $perPage);
    }

    public function listActive(array $filters = [], int $perPage = 15)
    {
        $filters['active_only'] = true;
        return $this->tripRepository->paginate($filters, $perPage);
    }

    public function listTodayRunning(array $filters = [], int $perPage = 15)
    {
        $dayIndex               = (int) now()->dayOfWeek; // 0 (Sun) - 6 (Sat)
        $filters['day_index']   = $dayIndex;
        $filters['active_only'] = true;
        return $this->tripRepository->paginate($filters, $perPage);
    }

    public function getBusesBetweenStations(int $from, int $to)
    {
        return $this->tripRepository->tripBusesWithoutWait($from, $to);
    }

    protected function validateTripInputs(array $data, ?int $excludeTripId = null): void
    {
        if (($data['arrival_time'] ?? null) <= ($data['departure_time'] ?? null)) {
            throw ValidationException::withMessages(['arrival_time' => ['arrival_time must be greater than departure_time.']]);
        }

        $days = $data['days_of_week'] ?? null;
        if (! is_array($days) || count($days) !== 7) {
            throw ValidationException::withMessages(['days_of_week' => ['days_of_week must be an array of exactly 7 boolean values.']]);
        }

        foreach ($days as $value) {
            if (! is_bool($value) && ! in_array($value, [0, 1, '0', '1'], true)) {
                throw ValidationException::withMessages(['days_of_week' => ['days_of_week must contain only boolean values.']]);
            }
        }

        $bus = Bus::with('operator')->find($data['bus_id']);
        if (! $bus || ! $bus->is_active) {
            throw ValidationException::withMessages(['bus_id' => ['Bus must exist and be active.']]);
        }

        if (! $bus->operator || ! $bus->operator->is_active) {
            throw ValidationException::withMessages(['bus_id' => ['Bus must belong to an active operator.']]);
        }

        $route = TransitRoute::find($data['route_id']);
        if (! $route || ! $route->is_active) {
            throw ValidationException::withMessages(['route_id' => ['Route must exist and be active.']]);
        }

        if ($this->tripRepository->existsDuplicate((int) $data['bus_id'], (int) $data['route_id'], (string) $data['departure_time'], $excludeTripId)) {
            throw ValidationException::withMessages(['departure_time' => ['Duplicate trip exists for the same bus, route and departure_time.']]);
        }

        if ($this->tripRepository->hasOverlap((int) $data['bus_id'], (string) $data['departure_time'], (string) $data['arrival_time'], $excludeTripId)) {
            throw ValidationException::withMessages(['departure_time' => ['Trip overlaps with an existing trip for this bus.']]);
        }

        if (($data['status'] ?? 'Active') === 'Active' && ! $bus->is_active) {
            throw ValidationException::withMessages(['status' => ['Inactive buses cannot have active trips.']]);
        }
    }
}
