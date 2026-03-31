<?php

namespace App\Repositories\Interfaces;

interface TripRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);

    public function existsDuplicate(int $busId, int $routeId, string $departureTime, ?int $excludeId = null): bool;
    public function hasOverlap(int $busId, string $departureTime, string $arrivalTime, ?int $excludeId = null): bool;

    public function tripBusesByWaitTime(int $from, int $to);
    public function tripBusesWithoutWait(int $from, int $to);
}
