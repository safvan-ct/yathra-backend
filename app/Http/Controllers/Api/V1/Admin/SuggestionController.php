<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\SuggestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSuggestionStatusRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\SuggestionResource;
use App\Repositories\Interfaces\SuggestionRepositoryInterface;
use App\Services\SuggestionService;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class SuggestionController extends Controller
{
    public function __construct(
        protected SuggestionRepositoryInterface $suggestionRepository,
        protected SuggestionService $suggestionService
    ) {}

    public function index(Request $request)
    {
        $filters = [];
        if ($request->has('status')) {
            $filters['status'] = $request->input('status');
        }

        $suggestions = $this->suggestionRepository->paginate($filters, $request->input('per_page', 15));

        return ApiResponse::paginated(SuggestionResource::collection($suggestions));
    }

    public function show(int $id)
    {
        $suggestion = $this->suggestionRepository->find($id);
        if (! $suggestion) {
            return ApiResponse::error('Suggestion not found', null, 404);
        }

        return ApiResponse::success(new SuggestionResource($suggestion));
    }

    public function review(UpdateSuggestionStatusRequest $request, int $id)
    {
        $this->validateStaff($request);

        $adminId     = $request->user()->id;
        $statusValue = $request->input('status');
        $statusEnum  = SuggestionStatus::tryFrom($statusValue);
        $reviewNote  = $request->input('review_note');

        try {
            $suggestion = $this->suggestionService->reviewSuggestion($id, $statusEnum, $adminId, $reviewNote);
            return ApiResponse::success(new SuggestionResource($suggestion));
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to review suggestion', $e->getMessage(), 400);
        }
    }

    protected function validateStaff(Request $request)
    {
        $user = $request->user();
        if (! $user || ! ($user instanceof \App\Models\Staff)) {
            throw new UnauthorizedException('Unauthorized');
        }
    }
}
