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
            'error' => 'Configuration issue',
            'detail' => $this->getMessage()
        ], 500);
    }
}
