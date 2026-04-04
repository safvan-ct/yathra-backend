<?php
namespace App\Services;

use App\Repositories\Interfaces\CityRepositoryInterface;

class CityService
{
    public function __construct(
        protected CityRepositoryInterface $cityRepository
    ) {}

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->cityRepository->paginate($filters, $perPage);
    }

    public function create(array $data)
    {
        return $this->cityRepository->create($data);
    }

    public function get(int $id)
    {
        return $this->cityRepository->find($id);
    }

    public function update(int $id, array $data)
    {
        return $this->cityRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->cityRepository->delete($id);
    }

    public function validateImport(array $rows)
    {
        $results = ['valid' => [], 'invalid' => []];

        foreach ($rows as $index => $row) {
            $errors = [];
            if (empty($row['name'])) {
                $errors[] = "Name is required";
            }

            if (empty($row['state_id'])) {
                $errors[] = "State ID is required";
            }

            if (empty($row['district_id'])) {
                $errors[] = "District ID is required";
            }

            if (count($errors) > 0) {
                $row['errors']        = $errors;
                $results['invalid'][] = $row;
            } else {
                $results['valid'][] = [
                    'name'        => $row['name'],
                    'local_name'  => $row['local_name'] ?? $row['name'],
                    'code'        => $row['code'],
                    'district_id' => $row['district_id'],
                    'is_active'   => true,
                    'created_at'  => now()->format('Y-m-d H:i:s'),
                    'updated_at'  => now()->format('Y-m-d H:i:s'),
                ];
            }
        }

        return $results;
    }

    public function bulkStore(array $data)
    {
        return $this->cityRepository->bulkCreate($data);
    }

    public function toggleStatus(int $id, string $column = 'is_active')
    {
        $city = $this->get($id);
        return $this->cityRepository->update($id, [
            $column => ! $city->$column,
        ]);
    }
}
