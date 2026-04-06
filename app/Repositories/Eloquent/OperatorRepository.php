<?php
namespace App\Repositories\Eloquent;

use App\Models\Operator;
use App\Repositories\Interfaces\OperatorRepositoryInterface;

class OperatorRepository implements OperatorRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15)
    {
        return $this->getQuery($filters)->paginate($perPage);
    }

    public function getQuery(array $filters = [])
    {
        $query = Operator::query();

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')->orWhere('phone', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query->orderBy('name');
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
