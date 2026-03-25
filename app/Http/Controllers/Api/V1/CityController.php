<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\CityResource;
use App\Services\CityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController
{
    public function __construct(
        protected CityService $cityService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $cities = $this->cityService->list($request->only(['search', 'district_id', 'is_active']), $request->input('per_page', 15));
        return ApiResponse::success(CityResource::collection($cities), 'Cities retrieved', 200);
    }

    public function store(StoreCityRequest $request): JsonResponse
    {
        $city = $this->cityService->create($request->validated());
        return ApiResponse::success(new CityResource($city), 'City created', 201);
    }

    public function show(int $id): JsonResponse
    {
        $city = $this->cityService->get($id);
        if (! $city) {
            return ApiResponse::error('City not found', null, 404);
        }

        return ApiResponse::success(new CityResource($city), 'City retrieved', 200);
    }

    public function update(UpdateCityRequest $request, int $id): JsonResponse
    {
        $city = $this->cityService->update($id, $request->validated());
        if (! $city) {
            return ApiResponse::error('City not found', null, 404);
        }

        return ApiResponse::success(new CityResource($city), 'City updated', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->cityService->delete($id);
        if (! $deleted) {
            return ApiResponse::error('City not found', null, 404);
        }

        return ApiResponse::success(null, 'City deleted', 200);
    }
}
