<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSuggestionRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\SuggestionResource;
use App\Repositories\Interfaces\SuggestionRepositoryInterface;
use App\Services\SuggestionService;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function __construct(
        protected SuggestionRepositoryInterface $suggestionRepository,
        protected SuggestionService $suggestionService
    ) {}

    public function index(Request $request)
    {
        $suggestions = $this->suggestionRepository->paginate(['user_id' => $request->user()->id], $request->input('per_page', 15));
        return ApiResponse::paginated(SuggestionResource::collection($suggestions));
    }

    public function store(StoreSuggestionRequest $request)
    {
        $this->suggestionService->validateUser($request);

        $data                  = $request->validated();
        $data['user_id']       = $request->user()->id;
        $data['proposed_data'] = $request->input('proposed_data'); // Risky for production

        $suggestion = $this->suggestionService->submitSuggestion($data);

        return ApiResponse::success(new SuggestionResource($suggestion), 'Suggestion created', 201);
    }

    public function show(Request $request, int $id)
    {
        $this->suggestionService->validateUser($request);

        $suggestion = $this->suggestionRepository->find($id);
        if (! $suggestion || $suggestion->user_id !== $request->user()->id) {
            return ApiResponse::error('Suggestion not found', null, 404);
        }

        return ApiResponse::success(new SuggestionResource($suggestion));
    }

    public function destroy(Request $request, int $id)
    {
        $this->suggestionService->validateUser($request);

        $suggestion = $this->suggestionRepository->find($id);
        if (! $suggestion || $suggestion->user_id !== $request->user()->id) {
            return ApiResponse::error('Suggestion not found', null, 404);
        }

        if ($suggestion->status->value !== 'Pending') {
            return ApiResponse::error('Only pending suggestions can be deleted', null, 403);
        }

        $this->suggestionRepository->delete($id);
        return ApiResponse::success(null, 'Deleted successfully');
    }
}
