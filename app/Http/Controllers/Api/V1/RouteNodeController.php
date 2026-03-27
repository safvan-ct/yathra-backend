<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreRouteNodeRequest;
use App\Http\Requests\UpdateRouteNodeRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\RouteNodeResource;
use App\Services\RouteNodeService;
use Illuminate\Http\JsonResponse;

class RouteNodeController
{
    public function __construct(
        protected RouteNodeService $routeNodeService
    ) {}

    public function index(int $route): JsonResponse
    {
        $nodes = $this->routeNodeService->list($route);
        return ApiResponse::success(RouteNodeResource::collection($nodes), 'Route nodes retrieved', 200);
    }

    public function store(StoreRouteNodeRequest $request, int $route): JsonResponse
    {
        $nodes = $this->routeNodeService->create($route, $request->validated());
        if ($nodes === null) {
            return ApiResponse::error('Route not found', null, 404);
        }

        return ApiResponse::success(RouteNodeResource::collection($nodes), 'Route node created', 201);
    }

    public function update(UpdateRouteNodeRequest $request, int $route, int $id): JsonResponse
    {
        $nodes = $this->routeNodeService->update($route, $id, $request->validated());
        if ($nodes === null) {
            return ApiResponse::error('Route not found', null, 404);
        }

        if ($nodes === false) {
            return ApiResponse::error('Route node not found', null, 404);
        }

        return ApiResponse::success(RouteNodeResource::collection($nodes), 'Route node updated', 200);
    }

    public function destroy(int $route, int $id): JsonResponse
    {
        $deleted = $this->routeNodeService->delete($route, $id);
        if ($deleted === null) {
            return ApiResponse::error('Route not found', null, 404);
        }

        if ($deleted === false) {
            return ApiResponse::error('Route node not found', null, 404);
        }

        return ApiResponse::success(null, 'Route node deleted', 200);
    }
}
