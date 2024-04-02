<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\Menu;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\Menu\MenuConfigService;

final class MenuConfigController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param MenuConfigService $menuConfigService
    */
    public function __construct(
        private MenuConfigService $menuConfigService
    ) { }
    
    /**
     * Get the configuration for a menu component.
     * 
     * @param Request $request
     *
     * @return JsonResponse
    */
    public function getConfig(Request $request) : JsonResponse
    {
        $config = $this->menuConfigService->getMenuConfig($request->user());
        return response()->json($config);
    }
}
