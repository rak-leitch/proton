<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\View;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\ViewConfig\UpdateConfigService;
use Adepta\Proton\Services\Auth\AuthorisationService;

class EntityUpdateController extends BaseController
{
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param UpdateConfigService $updateConfigService
     * @param AuthorisationService $authorisationService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private UpdateConfigService $updateConfigService,
        private AuthorisationService $authorisationService,
    ) { }
    
    /**
     * Get the configuration for an entity index page.
     * 
     * @param Request $request
     * @param string $entityCode
     * @param int $entityId
     *
     * @return JsonResponse
    */
    public function getConfig(Request $request, string $entityCode, int $entityId) : JsonResponse
    {
        $viewConfig = [];
        $entity = $this->entityFactory->create($entityCode);
        $modelClass = $entity->getModel();
        $model = $modelClass::findOrFail($entityId);
        $this->authorisationService->canUpdate($request->user(), $model, true);
        $viewConfig = $this->updateConfigService->getViewConfig($entity, $model);
        
        return response()->json($viewConfig);
    }
}
