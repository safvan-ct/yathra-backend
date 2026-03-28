<?php
namespace App\Repositories\Eloquent;

use App\Models\Suggestion;
use App\Repositories\Interfaces\SuggestionRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class SuggestionRepository implements SuggestionRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Suggestion::with(['user', 'admin', 'suggestable']);

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data): Suggestion
    {
        return Suggestion::create($data);
    }

    public function find(int $id): ?Suggestion
    {
        return Suggestion::with(['user', 'suggestable'])->find($id);
    }

    public function update(int $id, array $data): bool
    {
        $suggestion = $this->find($id);
        if (! $suggestion) {
            return false;
        }

        return $suggestion->update($data);
    }

    public function delete(int $id): bool
    {
        $suggestion = $this->find($id);
        if (! $suggestion) {
            return false;
        }

        return $suggestion->delete();
    }
}
