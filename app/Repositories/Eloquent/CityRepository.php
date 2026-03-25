<?php
namespace App\Repositories\Eloquent;

use App\Models\City;
use App\Repositories\Interfaces\CityRepositoryInterface;

class CityRepository implements CityRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        $query = City::query();

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('local_name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('code', 'like', '%' . $filters['search'] . '%');
        }

        if (! empty($filters['district_id'])) {
            $query->where('district_id', $filters['district_id']);
        }

        if (isset($filters['active'])) {
            $query->where('is_active', (bool) $filters['active']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function find(int $id)
    {
        return City::with('district.state')->find($id);
    }

    public function create(array $data)
    {
        return City::create($data);
    }

    public function update(int $id, array $data)
    {
        $city = City::find($id);
        if (! $city) {
            return null;
        }

        $city->update($data);
        return $city;
    }

    public function delete(int $id)
    {
        $city = City::find($id);
        if (! $city) {
            return false;
        }

        $city->delete();
        return true;
    }
}
