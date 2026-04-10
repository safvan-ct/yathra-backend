<?php
namespace App\Repositories\Eloquent;

use App\Models\Station;
use App\Repositories\Interfaces\StationRepositoryInterface;

class StationRepository implements StationRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15, bool $withInfo = false)
    {
        $query  = Station::query();
        $search = $filters['search'];

        if ($withInfo) {
            $query->with(['city', 'localBody']);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')->orWhere('local_name', 'like', '%' . $search . '%');
            });

            $query->orderByRaw("
                CASE
                    WHEN name LIKE ? THEN 1
                    WHEN local_name LIKE ? THEN 2
                    WHEN name LIKE ? THEN 3
                    WHEN local_name LIKE ? THEN 4
                    ELSE 5
                END
                ", ["{$search}%", "{$search}%", "{$search}%", "%{$search}%"]
            );
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

    public function bulkCreate(array $data)
    {
        return Station::insert($data);
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
