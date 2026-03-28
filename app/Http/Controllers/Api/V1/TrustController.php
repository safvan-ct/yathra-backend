<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class TrustController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        if (! $user || ! ($user instanceof User)) {
            throw new UnauthorizedException('Unauthorized');
        }

        return ApiResponse::success(['trust_score' => $user->trust_score, 'trust_level' => $user->trust_level]);
    }

    public function leaderboard(Request $request)
    {
        $limit = $request->input('limit', 50);
        $users = User::orderByDesc('trust_score')->limit($limit)->get();

        return ApiResponse::success(UserResource::collection($users));
    }
}
