<?php
namespace App\Services;

use App\Repositories\Interfaces\StationRepositoryInterface;

class StationService
{
    public function __construct(
        protected StationRepositoryInterface $stationRepository
    ) {}

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->stationRepository->paginate($filters, $perPage);
    }

    public function create(array $data)
    {
        return $this->stationRepository->create($data);
    }

    public function get(int $id)
    {
        return $this->stationRepository->find($id);
    }

    public function update(int $id, array $data)
    {
        return $this->stationRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->stationRepository->delete($id);
    }

    public function validateImport(array $rows)
    {
        $results = ['valid' => [], 'invalid' => []];

        foreach ($rows as $index => $row) {
            $errors = [];
            if (empty($row['name'])) {
                $errors[] = "Name is required";
            }
            if (empty($row['city_id'])) {
                $errors[] = "City ID is required";
            }

            if (count($errors) > 0) {
                $row['errors']        = $errors;
                $results['invalid'][] = $row;
            } else {
                $results['valid'][] = [
                    'name'       => $row['name'],
                    'local_name' => $row['local_name'] ?? $row['name'],
                    'city_id'    => $row['city_id'],
                    'latitude'   => $row['latitude'] ?? null,
                    'longitude'  => $row['longitude'] ?? null,
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        return $results;
    }

    public function bulkStore(array $data)
    {
        return $this->stationRepository->bulkCreate($data);
    }

    public function toggleStatus(int $id, string $column = 'is_active')
    {
        $station = $this->get($id);
        return $this->stationRepository->update($id, [
            $column => ! $station->$column,
        ]);
    }
}
