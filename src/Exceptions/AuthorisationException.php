<?php declare(strict_types = 1);

namespace Adepta\Proton\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Exception;

class AuthorisationException extends Exception
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
            'error' => 'Authorisation issue',
            'detail' => $this->getMessage()
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
