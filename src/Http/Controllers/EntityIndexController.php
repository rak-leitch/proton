<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\List\ListConfigService;

class EntityIndexController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private ListConfigService $listConfigService,
    ) { }
    
    /**
     * Get the configuration for an entity index page.
     *
     * @return JsonResponse
    */
    public function getConfig(Request $request, string $entityCode) : JsonResponse
    {
        $listFieldsConfig = [];
        $entity = $this->entityFactory->create($entityCode);
        $listFieldsConfig = $this->listConfigService->getListConfig($entity);
        
        return response()->json([
            'list_fields' => $listFieldsConfig,
        ]);
    }
}
