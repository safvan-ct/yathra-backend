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
        $deleted = $this->districtRepository->delete($id);
        return $deleted;
    }
}
