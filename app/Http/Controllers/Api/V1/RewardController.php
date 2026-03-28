<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\LeaderboardResource;
use App\Http\Resources\RewardResource;
use App\Repositories\Interfaces\RewardRepositoryInterface;
use App\Services\RewardService;
use App\Services\SuggestionService;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function __construct(
        protected RewardRepositoryInterface $rewardRepository,
        protected RewardService $rewardService,
        protected SuggestionService $suggestionService
    ) {}

    public function getPoints(Request $request)
    {
        $this->suggestionService->validateUser($request);

        $userId      = $request->user()->id;
        $totalPoints = $this->rewardService->getTotalPoints($userId);

        return ApiResponse::success(['total_points' => $totalPoints]);
    }

    public function history(Request $request)
    {
        $this->suggestionService->validateUser($request);

        $userId  = $request->user()->id;
        $perPage = $request->input('per_page', 15);

        $rewards = $this->rewardRepository->getUserRewards($userId, $perPage);

        return ApiResponse::paginated(RewardResource::collection($rewards));
    }

    public function leaderboard(Request $request)
    {
        $user   = $request->user();
        $userId = $user instanceof \App\Models\User  ? $user->id : null;

        $limit = $request->input('limit', 50);
        $users = $this->rewardRepository->getLeaderboard($limit, $userId);

        return ApiResponse::success(LeaderboardResource::collection($users));
    }
}
