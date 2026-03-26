<?php
namespace App\Services;

use App\Repositories\Interfaces\StationRepositoryInterface;

class StationService
{
    public function __construct(
        protected StationRepositoryInterface $stationRepository,
        protected ActivityLogService $activityLogService
    ) {}

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->stationRepository->paginate($filters, $perPage);
    }

    public function create(array $data)
    {
        $station = $this->stationRepository->create($data);
        $this->activityLogService->log('station_created', 'App\\Models\\Station', $station->id, $station->toArray());
        return $station;
    }

    public function get(int $id)
    {
        return $this->stationRepository->find($id);
    }

    public function update(int $id, array $data)
    {
        $station = $this->stationRepository->update($id, $data);
        if ($station) {
            $this->activityLogService->log('station_updated', 'App\\Models\\Station', $station->id, $station->toArray());
        }

        return $station;
    }

    public function delete(int $id)
    {
        $deleted = $this->stationRepository->delete($id);
        if ($deleted) {
            $this->activityLogService->log('station_deleted', 'App\\Models\\Station', $id, null);
        }

        return $deleted;
    }
}
