<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreOperatorRequest;
use App\Http\Requests\UpdateOperatorRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\OperatorResource;
use App\Services\OperatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OperatorController
{
    public function __construct(
        protected OperatorService $operatorService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $operators = $this->operatorService->list($request->only(['search', 'type', 'is_active']), $request->input('per_page', 15));
        return ApiResponse::paginated(OperatorResource::collection($operators), 'Operators retrieved', 200);
    }

    public function store(StoreOperatorRequest $request): JsonResponse
    {
        $operator = $this->operatorService->create($request->validated());
        return ApiResponse::success(new OperatorResource($operator), 'Operator created', 201);
    }

    public function show(int $id): JsonResponse
    {
        $operator = $this->operatorService->get($id);
        if (! $operator) {
            return ApiResponse::error('Operator not found', null, 404);
        }

        return ApiResponse::success(new OperatorResource($operator), 'Operator retrieved', 200);
    }

    public function update(UpdateOperatorRequest $request, int $id): JsonResponse
    {
        $operator = $this->operatorService->update($id, $request->validated());
        if (! $operator) {
            return ApiResponse::error('Operator not found', null, 404);
        }

        return ApiResponse::success(new OperatorResource($operator), 'Operator updated', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->operatorService->delete($id);
        if (! $deleted) {
            return ApiResponse::error('Operator not found', null, 404);
        }

        return ApiResponse::success(null, 'Operator deleted', 200);
    }
}
