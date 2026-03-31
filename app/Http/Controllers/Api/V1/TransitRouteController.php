<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreTransitRouteRequest;
use App\Http\Requests\UpdateTransitRouteRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\TransitRouteResource;
use App\Services\TransitRouteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransitRouteController
{
    public function __construct(
        protected TransitRouteService $transitRouteService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $routes = $this->transitRouteService->list($request->only(['origin_id', 'destination_id', 'variant', 'search', 'is_active']), $request->input('per_page', 15));
        return ApiResponse::paginated(TransitRouteResource::collection($routes), 'Routes retrieved', 200);
    }

    public function store(StoreTransitRouteRequest $request): JsonResponse
    {
        $route = $this->transitRouteService->create($request->validated());
        return ApiResponse::success(new TransitRouteResource($route), 'Route created', 201);
    }

    public function show(int $id): JsonResponse
    {
        $route = $this->transitRouteService->get($id);
        if (! $route) {
            return ApiResponse::error('Route not found', null, 404);
        }

        return ApiResponse::success(new TransitRouteResource($route), 'Route retrieved', 200);
    }

    public function update(UpdateTransitRouteRequest $request, int $id): JsonResponse
    {
        $route = $this->transitRouteService->update($id, $request->validated());
        if (! $route) {
            return ApiResponse::error('Route not found', null, 404);
        }

        return ApiResponse::success(new TransitRouteResource($route), 'Route updated', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->transitRouteService->delete($id);
        if (! $deleted) {
            return ApiResponse::error('Route not found', null, 404);
        }

        return ApiResponse::success(null, 'Route deleted', 200);
    }
}
