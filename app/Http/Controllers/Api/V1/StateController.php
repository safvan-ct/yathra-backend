<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStateRequest;
use App\Http\Requests\UpdateStateRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\StateResource;
use App\Services\StateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function __construct(protected StateService $stateService)
    {}

    public function index(Request $request): JsonResponse
    {
        $states = $this->stateService->list($request->only(['search', 'active']), $request->input('per_page', 15));
        return ApiResponse::success(StateResource::collection($states), 'States retrieved', 200);
    }

    public function store(StoreStateRequest $request): JsonResponse
    {
        $state = $this->stateService->create($request->validated());
        return ApiResponse::success(new StateResource($state), 'State created', 201);
    }

    public function show(int $id): JsonResponse
    {
        $state = $this->stateService->get($id);
        if (! $state) {
            return ApiResponse::error('State not found', null, 404);
        }

        return ApiResponse::success(new StateResource($state), 'State retrieved', 200);
    }

    public function update(UpdateStateRequest $request, int $id): JsonResponse
    {
        $state = $this->stateService->update($id, $request->validated());
        if (! $state) {
            return ApiResponse::error('State not found', null, 404);
        }

        return ApiResponse::success(new StateResource($state), 'State updated', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->stateService->delete($id);
        if (! $deleted) {
            return ApiResponse::error('State not found', null, 404);
        }

        return ApiResponse::success(null, 'State deleted', 200);
    }
}
