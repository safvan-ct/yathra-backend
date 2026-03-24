<?php
namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json(['status' => true, 'message' => $message, 'data' => $data], $statusCode);
    }

    public static function error(string $message = 'Error', $errors = null, int $statusCode = 400): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $message, 'errors' => $errors], $statusCode);
    }

    public static function paginated($data, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status'     => true,
            'message'    => $message,
            'data'       => $data->items(),
            'pagination' => [
                'total'        => $data->total(),
                'count'        => $data->count(),
                'per_page'     => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages'  => $data->lastPage(),
            ],
        ], $statusCode);
    }
}
