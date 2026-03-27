<?php
namespace App\Repositories\Eloquent;

use App\Models\Operator;
use App\Repositories\Interfaces\OperatorRepositoryInterface;

class OperatorRepository implements OperatorRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        $query = Operator::query();

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function find(int $id)
    {
        return Operator::with('buses')->find($id);
    }

    public function create(array $data)
    {
        return Operator::create($data);
    }

    public function update(int $id, array $data)
    {
        $operator = Operator::find($id);
        if (! $operator) {
            return null;
        }

        $operator->update($data);
        return $operator->fresh('buses');
    }

    public function delete(int $id)
    {
        $operator = Operator::find($id);
        if (! $operator) {
            return false;
        }

        return $operator->delete();
    }
}
