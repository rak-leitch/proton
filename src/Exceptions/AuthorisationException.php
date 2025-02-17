<?php declare(strict_types = 1);

namespace Adepta\Proton\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

final class AuthorisationException extends Exception
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
            'error' => 'Forbidden',
            'detail' => app()->hasDebugModeEnabled() ? $this->getMessage() : ''
        ], 403);
    }

    /**
     * Do not log authorisation exceptions.
     *
     * @return bool
    */
    public function report(): bool
    {
        return true;
    }
}
