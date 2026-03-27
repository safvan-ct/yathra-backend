<?php
namespace App\Repositories\Eloquent;

use App\Models\Bus;
use App\Repositories\Interfaces\BusRepositoryInterface;

class BusRepository implements BusRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        $query = Bus::query()->with('operator');

        if (! empty($filters['operator_id'])) {
            $query->where('operator_id', $filters['operator_id']);
        }

        if (! empty($filters['search'])) {
            $query->where('bus_number', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query->orderBy('bus_number')->paginate($perPage);
    }

    public function find(int $id)
    {
        return Bus::with('operator')->find($id);
    }

    public function create(array $data)
    {
        return Bus::create($data);
    }

    public function update(int $id, array $data)
    {
        $bus = Bus::find($id);
        if (! $bus) {
            return null;
        }

        $bus->update($data);
        return $bus->fresh('operator');
    }

    public function delete(int $id)
    {
        $bus = Bus::find($id);
        if (! $bus) {
            return false;
        }

        return $bus->delete();
    }
}
