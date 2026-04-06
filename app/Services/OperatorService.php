<?php
namespace App\Services;

use App\Repositories\Interfaces\OperatorRepositoryInterface;

class OperatorService
{
    public function __construct(
        protected OperatorRepositoryInterface $operatorRepository
    ) {}

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->operatorRepository->paginate($filters, $perPage);
    }

    public function query(array $filters = [])
    {
        return $this->operatorRepository->getQuery($filters);
    }

    public function get(int $id)
    {
        return $this->operatorRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->operatorRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->operatorRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->operatorRepository->delete($id);
    }
}
