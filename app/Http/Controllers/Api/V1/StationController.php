<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreStationRequest;
use App\Http\Requests\UpdateStationRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\StationResource;
use App\Services\StationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StationController
{
    public function __construct(
        protected StationService $stationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $stations = $this->stationService->list($request->only(['search', 'city_id', 'type']), $request->input('per_page', 15));
        return ApiResponse::paginated(StationResource::collection($stations), 'Stations retrieved', 200);
    }

    public function store(StoreStationRequest $request): JsonResponse
    {
        $station = $this->stationService->create($request->validated());
        return ApiResponse::success(new StationResource($station), 'Station created', 201);
    }

    public function show(int $id): JsonResponse
    {
        $station = $this->stationService->get($id);
        if (! $station) {
            return ApiResponse::error('Station not found', null, 404);
        }

        return ApiResponse::success(new StationResource($station), 'Station retrieved', 200);
    }

    public function update(UpdateStationRequest $request, int $id): JsonResponse
    {
        $station = $this->stationService->update($id, $request->validated());
        if (! $station) {
            return ApiResponse::error('Station not found', null, 404);
        }

        return ApiResponse::success(new StationResource($station), 'Station updated', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->stationService->delete($id);
        if (! $deleted) {
            return ApiResponse::error('Station not found', null, 404);
        }

        return ApiResponse::success(null, 'Station deleted', 200);
    }
}
