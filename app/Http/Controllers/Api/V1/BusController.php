<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreBusRequest;
use App\Http\Requests\UpdateBusRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\BusResource;
use App\Services\BusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusController
{
    public function __construct(
        protected BusService $busService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $buses = $this->busService->list($request->only(['operator_id', 'search', 'is_active']), $request->input('per_page', 15));
        return ApiResponse::paginated(BusResource::collection($buses), 'Buses retrieved', 200);
    }

    public function store(StoreBusRequest $request): JsonResponse
    {
        $bus = $this->busService->create($request->validated());
        return ApiResponse::success(new BusResource($bus), 'Bus created', 201);
    }

    public function show(int $id): JsonResponse
    {
        $bus = $this->busService->get($id);
        if (! $bus) {
            return ApiResponse::error('Bus not found', null, 404);
        }

        return ApiResponse::success(new BusResource($bus), 'Bus retrieved', 200);
    }

    public function update(UpdateBusRequest $request, int $id): JsonResponse
    {
        $bus = $this->busService->update($id, $request->validated());
        if (! $bus) {
            return ApiResponse::error('Bus not found', null, 404);
        }

        return ApiResponse::success(new BusResource($bus), 'Bus updated', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->busService->delete($id);
        if (! $deleted) {
            return ApiResponse::error('Bus not found', null, 404);
        }

        return ApiResponse::success(null, 'Bus deleted', 200);
    }
}
