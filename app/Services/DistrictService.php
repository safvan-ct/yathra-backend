<?php
namespace App\Services;

use App\Repositories\Interfaces\DistrictRepositoryInterface;

class DistrictService
{
    public function __construct(
        protected DistrictRepositoryInterface $districtRepository
    ) {}

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->districtRepository->paginate($filters, $perPage);
    }

    public function create(array $data)
    {
        $district = $this->districtRepository->create($data);
        return $district;
    }

    public function get(int $id)
    {
        return $this->districtRepository->find($id);
    }

    public function update(int $id, array $data)
    {
        $district = $this->districtRepository->update($id, $data);
        return $district;
    }

    public function delete(int $id)
    {
        return $this->districtRepository->delete($id);
    }

    public function validateImport(array $rows)
    {
        $results = ['valid' => [], 'invalid' => []];

        foreach ($rows as $index => $row) {
            $errors = [];
            if (empty($row['name'])) {
                $errors[] = "Name is required";
            }
            if (empty($row['code'])) {
                $errors[] = "Code is required";
            }
            if (empty($row['state_id'])) {
                $errors[] = "State ID is required";
            }

            if (count($errors) > 0) {
                $row['errors']        = $errors;
                $results['invalid'][] = $row;
            } else {
                $results['valid'][] = [
                    'name'       => $row['name'],
                    'local_name' => $row['local_name'] ?? $row['name'],
                    'state_id'   => $row['state_id'],
                    'code'       => $row['code'],
                    'is_active'  => true,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ];
            }
        }

        return $results;
    }

    public function bulkStore(array $data)
    {
        return $this->districtRepository->bulkCreate($data);
    }

    public function toggleStatus(int $id, string $column = 'is_active')
    {
        $district = $this->get($id);
        return $this->districtRepository->update($id, [
            $column => ! $district->$column,
        ]);
    }
}
