<?php
namespace App\Repositories\Eloquent;

use App\Models\District;
use App\Repositories\Interfaces\DistrictRepositoryInterface;

class DistrictRepository implements DistrictRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        $query = District::with('state');

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('local_name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('code', 'like', '%' . $filters['search'] . '%');
        }

        if (! empty($filters['state_id'])) {
            $query->where('state_id', $filters['state_id']);
        }

        if (isset($filters['active'])) {
            $query->where('is_active', (bool) $filters['active']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function find(int $id)
    {
        return District::with(['state', 'cities'])->find($id);
    }

    public function create(array $data)
    {
        return District::create($data);
    }

    public function bulkCreate(array $data)
    {
        return District::insert($data);
    }

    public function update(int $id, array $data)
    {
        $district = District::find($id);
        if (! $district) {
            return null;
        }

        $district->update($data);
        return $district;
    }

    public function delete(int $id)
    {
        $district = District::find($id);
        if (! $district) {
            return false;
        }

        $district->delete();
        return true;
    }
}
