<?php

namespace CSlant\Blog\Api\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Build a success response.
     */
    public function successResponse(mixed $data = [], ?string $message = 'Success', ?int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            $data,
        ], $code);
    }

    /**
     * Build an error response.
     *
     * @param  array<string, mixed>  $data
     */
    public function errorResponse(string $message, int|string|null $code, ?array $data = []): JsonResponse
    {
        if (is_null($code) || (int) $code < 100) {
            $code = 400;
        }

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ], (int) $code);
    }
}
