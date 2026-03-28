<?php
namespace App\Repositories\Interfaces;

use App\Models\UserReward;
use Illuminate\Pagination\LengthAwarePaginator;

interface RewardRepositoryInterface
{
    public function getUserRewards(int $userId, int $perPage = 15): LengthAwarePaginator;
    public function getTotalPoints(int $userId): int;
    public function create(array $data): UserReward;
    public function existsForSource(int $sourceId): bool;
    public function getLeaderboard(int $limit = 50, ?int $userId = null);
}
