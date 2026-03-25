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
}
