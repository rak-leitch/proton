<?php declare(strict_types = 1);

namespace Adepta\Proton\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

final class RequestException extends Exception
{
    /**
     * Custom JSON response for frontend
     * 
     * @param Request $request
     *
     * @return JsonResponse
    */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => 'Bad request',
            'detail' => app()->hasDebugModeEnabled() ? $this->getMessage() : ''
        ], 400);
    }

    /**
     * Do not log request exceptions.
     *
     * @return bool
    */
    public function report(): bool
    {
        return true;
    }
}
