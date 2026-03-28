<?php
namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\UserReward;
use App\Repositories\Interfaces\RewardRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RewardRepository implements RewardRepositoryInterface
{
    public function getUserRewards(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return UserReward::with('suggestion')->where('user_id', $userId)->latest()->paginate($perPage);
    }

    public function getTotalPoints(int $userId): int
    {
        return UserReward::where('user_id', $userId)->sum('points');
    }

    public function create(array $data): UserReward
    {
        return UserReward::create($data);
    }

    public function existsForSource(int $sourceId): bool
    {
        return UserReward::where('source_id', $sourceId)->exists();
    }

    public function getLeaderboard(int $limit = 50, ?int $userId = null)
    {
        $rewards = UserReward::query()->select('user_id', DB::raw('SUM(points) as total_points'))->groupBy('user_id');

        return User::query()
            ->select('users.id', 'users.phone')
            ->leftJoinSub($rewards, 'rewards', fn($join) => $join->on('users.id', '=', 'rewards.user_id'))
            ->addSelect(DB::raw('COALESCE(rewards.total_points, 0) as total_points'))
            ->when($userId, fn($query) => $query->where('users.id', $userId))
            ->when(! $userId, fn($query) => $query->orderByDesc('total_points')->limit($limit))
            ->get();
    }
}
