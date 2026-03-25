<?php
namespace App\Repositories\Eloquent;

use App\Models\State;
use App\Repositories\Interfaces\StateRepositoryInterface;

class StateRepository implements StateRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        $query = State::query();

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('local_name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('code', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['active'])) {
            $query->where('is_active', (bool) $filters['active']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function find(int $id)
    {
        return State::with('districts')->find($id);
    }

    public function create(array $data)
    {
        return State::create($data);
    }

    public function update(int $id, array $data)
    {
        $state = State::find($id);
        if (! $state) {
            return null;
        }

        $state->update($data);
        return $state;
    }

    public function delete(int $id)
    {
        $state = State::find($id);
        if (! $state) {
            return false;
        }

        $state->delete();
        return true;
    }
}
