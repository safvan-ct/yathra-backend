<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\TripResource;
use App\Services\TripService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TripController
{
    public function __construct(
        protected TripService $tripService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $trips = $this->tripService->list($request->only(['route_id', 'bus_id', 'operator_id', 'status']), $request->input('per_page', 15));
        return ApiResponse::paginated(TripResource::collection($trips), 'Trips retrieved', 200);
    }

    public function store(StoreTripRequest $request): JsonResponse
    {
        $trip = $this->tripService->create($request->validated());
        return ApiResponse::success(new TripResource($trip), 'Trip created', 201);
    }

    public function show(int $id): JsonResponse
    {
        $trip = $this->tripService->get($id);
        if (! $trip) {
            return ApiResponse::error('Trip not found', null, 404);
        }

        return ApiResponse::success(new TripResource($trip), 'Trip retrieved', 200);
    }

    public function update(UpdateTripRequest $request, int $id): JsonResponse
    {
        $trip = $this->tripService->update($id, $request->validated());
        if (! $trip) {
            return ApiResponse::error('Trip not found', null, 404);
        }

        return ApiResponse::success(new TripResource($trip), 'Trip updated', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->tripService->delete($id);
        if (! $deleted) {
            return ApiResponse::error('Trip not found', null, 404);
        }

        return ApiResponse::success(null, 'Trip deleted', 200);
    }

    public function active(Request $request): JsonResponse
    {
        $trips = $this->tripService->listActive($request->only(['route_id', 'bus_id', 'operator_id', 'status']), $request->input('per_page', 15));
        return ApiResponse::paginated(TripResource::collection($trips), 'Active trips retrieved', 200);
    }

    public function byDay(Request $request, int $dayIndex): JsonResponse
    {
        $trips = $this->tripService->getByDayIndex($dayIndex, $request->only(['route_id', 'bus_id', 'operator_id', 'status']), $request->input('per_page', 15));
        return ApiResponse::paginated(TripResource::collection($trips), 'Trips retrieved', 200);
    }

    public function today(Request $request): JsonResponse
    {
        $trips = $this->tripService->listTodayRunning($request->only(['route_id', 'bus_id', 'operator_id', 'status']), $request->input('per_page', 15));
        return ApiResponse::paginated(TripResource::collection($trips), "Today's running trips retrieved", 200);
    }
}
