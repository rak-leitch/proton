<?php declare(strict_types = 1);

namespace Adepta\Proton\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

final class ConfigurationException extends Exception
{
    /**
     * Custom JSON response for frontend
     * 
     * @param Request $request
     *
     * @return JsonResponse
    */
    public function render(Request $request) : JsonResponse
    {
        return response()->json([
            'error' => 'Bad Configuration',
            'detail' => app()->hasDebugModeEnabled() ? $this->getMessage() : ''
        ], 500);
    }
}
