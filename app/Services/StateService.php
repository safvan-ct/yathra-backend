<?php
namespace App\Services;

use App\Repositories\Interfaces\StateRepositoryInterface;

class StateService
{
    public function __construct(
        protected StateRepositoryInterface $stateRepository
    ) {}

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->stateRepository->paginate($filters, $perPage);
    }

    public function create(array $data)
    {
        $state = $this->stateRepository->create($data);
        return $state;
    }

    public function get(int $id)
    {
        return $this->stateRepository->find($id);
    }

    public function update(int $id, array $data)
    {
        $state = $this->stateRepository->update($id, $data);
        return $state;
    }

    public function delete(int $id)
    {
        $deleted = $this->stateRepository->delete($id);
        return $deleted;
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

            if (count($errors) > 0) {
                $row['errors']        = $errors;
                $results['invalid'][] = $row;
            } else {
                $results['valid'][] = [
                    'name'       => $row['name'],
                    'local_name' => $row['local_name'] ?? $row['name'],
                    'code'       => strtoupper($row['code']),
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
        return $this->stateRepository->bulkCreate($data);
    }

    public function toggleStatus(int $id, string $column = 'is_active')
    {
        $state = $this->get($id);
        return $this->stateRepository->update($id, [
            $column => ! $state->$column,
        ]);
    }
}
