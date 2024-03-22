<?php declare(strict_types = 1);

namespace Adepta\Proton\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

final class EntityNotFoundException extends Exception
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
            'error' => 'Not found',
            'detail' => app()->hasDebugModeEnabled() ? $this->getMessage() : ''
        ], 404);
    }

    /**
     * Do not log these exceptions.
     *
     * @return bool
    */
    public function report(): bool
    {
        return true;
    }
}
