<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDistrictRequest;
use App\Http\Requests\UpdateDistrictRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\DistrictResource;
use App\Services\DistrictService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function __construct(
        protected DistrictService $districtService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $districts = $this->districtService->list($request->only(['search', 'state_id', 'is_active']), $request->input('per_page', 15));
        return ApiResponse::success(DistrictResource::collection($districts), 'Districts retrieved', 200);
    }

    public function store(StoreDistrictRequest $request): JsonResponse
    {
        $district = $this->districtService->create($request->validated());
        return ApiResponse::success(new DistrictResource($district), 'District created', 201);
    }

    public function show(int $id): JsonResponse
    {
        $district = $this->districtService->get($id);
        if (! $district) {
            return ApiResponse::error('District not found', null, 404);
        }

        return ApiResponse::success(new DistrictResource($district), 'District retrieved', 200);
    }

    public function update(UpdateDistrictRequest $request, int $id): JsonResponse
    {
        $district = $this->districtService->update($id, $request->validated());
        if (! $district) {
            return ApiResponse::error('District not found', null, 404);
        }

        return ApiResponse::success(new DistrictResource($district), 'District updated', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->districtService->delete($id);
        if (! $deleted) {
            return ApiResponse::error('District not found', null, 404);
        }

        return ApiResponse::success(null, 'District deleted', 200);
    }
}
