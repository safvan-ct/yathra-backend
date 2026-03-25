<?php
namespace App\Services;

use App\Repositories\Interfaces\CityRepositoryInterface;

class CityService
{
    public function __construct(
        protected CityRepositoryInterface $cityRepository,
        protected ActivityLogService $activityLogService
    ) {}

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->cityRepository->paginate($filters, $perPage);
    }

    public function create(array $data)
    {
        $city = $this->cityRepository->create($data);
        $this->activityLogService->log('city_created', 'App\\Models\\City', $city->id, 'App\\Models\\City', $city->id, $city->toArray());
        return $city;
    }

    public function get(int $id)
    {
        return $this->cityRepository->find($id);
    }

    public function update(int $id, array $data)
    {
        $city = $this->cityRepository->update($id, $data);
        if ($city) {
            $this->activityLogService->log('city_updated', 'App\\Models\\City', $city->id, 'App\\Models\\City', $city->id, $city->toArray());
        }

        return $city;
    }

    public function delete(int $id)
    {
        $deleted = $this->cityRepository->delete($id);
        if ($deleted) {
            $this->activityLogService->log('city_deleted', 'App\\Models\\City', $id, 'App\\Models\\City', $id, null);
        }

        return $deleted;
    }
}
