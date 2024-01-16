<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\View;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\ViewConfig\IndexConfigService;

class EntityIndexController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private IndexConfigService $indexConfigService,
    ) { }
    
    /**
     * Get the configuration for an entity index page.
     * 
     * @param Request $request
     * @param string $entityCode
     *
     * @return JsonResponse
    */
    public function getConfig(Request $request, string $entityCode) : JsonResponse
    {
        $listFieldsConfig = [];
        $entity = $this->entityFactory->create($entityCode);
        $listFieldsConfig = $this->indexConfigService->getViewConfig($entity);
        
        return response()->json($listFieldsConfig);
    }
}
