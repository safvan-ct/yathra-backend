<?php
namespace App\Services;

use App\Enums\RewardActivityType;
use App\Repositories\Interfaces\RewardRepositoryInterface;
use Exception;

class RewardService
{
    public function __construct(
        protected RewardRepositoryInterface $rewardRepository
    ) {}

    public function rewardUser(int $userId, int $suggestionId, RewardActivityType $activityType): void
    {
        if ($this->rewardRepository->existsForSource($suggestionId)) {
            throw new Exception("Reward already issued for this suggestion.");
        }

        $points = match ($activityType) {
            RewardActivityType::NewEntry     => 10,
            RewardActivityType::Update       => 7,
            RewardActivityType::Verification => 5,
            default                          => 0,
        };

        if ($points > 0) {
            $this->rewardRepository->create([
                'user_id'       => $userId,
                'source_id'     => $suggestionId,
                'activity_type' => $activityType->value,
                'points'        => $points,
            ]);
        }
    }

    public function getTotalPoints(int $userId): int
    {
        return $this->rewardRepository->getTotalPoints($userId);
    }
}
