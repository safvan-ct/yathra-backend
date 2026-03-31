<?php
namespace App\Repositories\Interfaces;

use App\Models\Suggestion;
use Illuminate\Pagination\LengthAwarePaginator;

interface SuggestionRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15, bool $withInfo = false): LengthAwarePaginator;
    public function create(array $data): Suggestion;
    public function find(int $id): ?Suggestion;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
