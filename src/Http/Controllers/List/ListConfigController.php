<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\List;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\List\ListConfigService;

class ListConfigController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param ListConfigService $listConfigService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private ListConfigService $listConfigService,
    ) { }
    
    /**
     * Get the configuration for an list component.
     * 
     * @param Request $request
     * @param string $viewType
     * @param string $entityCode
     *
     * @return JsonResponse
    */
    public function getConfig(Request $request, string $viewType, string $entityCode) : JsonResponse
    {
        $listConfig = [];
        $entity = $this->entityFactory->create($entityCode);
        $listConfig = $this->listConfigService->getListConfig($entity, $viewType);
        
        return response()->json($listConfig);
    }
}
