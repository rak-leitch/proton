<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\Display;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Entity\EntityFactory;
use Adepta\Proton\Services\Auth\AuthorisationService;
use Adepta\Proton\Services\Display\DisplayConfigService;

final class DisplayConfigController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param DisplayConfigService $displayConfigService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private AuthorisationService $authorisationService,
        private DisplayConfigService $displayConfigService,
    ) { }
    
    /**
     * Get the configuration for a display component.
     * 
     * @param Request $request
     * @param string $entityCode
     * @param int $entityId
     *
     * @return JsonResponse
    */
    public function getConfig(Request $request, string $entityCode, int $entityId) : JsonResponse
    {
        $entity = $this->entityFactory->create($entityCode);
        $model = $entity->getLoadedModel($entityId);
        $this->authorisationService->canView($request->user(), $model, true);
        $displayConfig = $this->displayConfigService->getDisplayConfig($entity, $model);
        
        return response()->json($displayConfig);
    }
}
