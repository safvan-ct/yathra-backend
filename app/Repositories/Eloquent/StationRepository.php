<?php
namespace App\Repositories\Eloquent;

use App\Models\Station;
use App\Repositories\Interfaces\StationRepositoryInterface;

class StationRepository implements StationRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        $query = Station::query()->with('city.district.state');

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('code', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('locale_name', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (! empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['active'])) {
            $query->where('is_active', (bool) $filters['active']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function find(int $id)
    {
        return Station::with('city.district.state')->find($id);
    }

    public function create(array $data)
    {
        return Station::create($data);
    }

    public function update(int $id, array $data)
    {
        $station = Station::find($id);
        if (! $station) {
            return null;
        }

        $station->update($data);
        return $station->fresh();
    }

    public function delete(int $id)
    {
        $station = Station::find($id);
        if (! $station) {
            return false;
        }

        return $station->delete();
    }
}
