<?php declare(strict_types = 1);

namespace Adepta\Proton\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Exception;

class ConfigurationException extends Exception
{
    /**
     * Custom JSON response for frontend
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function render(Request $request) : JsonResponse
    {
        return response()->json([
            'error' => 'Configuration issue',
            'detail' => $this->getMessage()
        ], 500);
    }
}
