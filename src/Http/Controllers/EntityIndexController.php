<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;

class EntityIndexController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param \Adepta\Proton\Services\EntityFactory $entityFactory
    */
    public function __construct(
        private EntityFactory $entityFactory,
    ) { }
    
    /**
     * Get the configuration for an entity index page.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function getConfig(Request $request, string $entityCode) : JsonResponse
    {
        $config = [];
        $entity = $this->entityFactory->create($entityCode);
        return response()->json($config);
    }
}
