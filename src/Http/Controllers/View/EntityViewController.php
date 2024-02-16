<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\View;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\ViewConfig\ViewConfigService;
use Adepta\Proton\Services\Auth\AuthorisationService;

final class EntityViewController extends BaseController
{
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param ViewConfigService $viewConfigService
     * @param AuthorisationService $authorisationService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private ViewConfigService $viewConfigService,
        private AuthorisationService $authorisationService,
    ) { }
    
    /**
     * Get the configuration for an entity view page.
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
        $this->authorisationService->canView($request->user(), $model, true);
        
        $viewConfig = $this->viewConfigService->getViewConfig($request->user(), $entity, $model);
        
        return response()->json($viewConfig);
    }
}
