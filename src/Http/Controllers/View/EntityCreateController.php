<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\View;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\ViewConfig\CreateConfigService;
use Adepta\Proton\Services\Auth\AuthorisationService;

final class EntityCreateController extends BaseController
{
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param CreateConfigService $createConfigService
     * @param AuthorisationService $authorisationService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private CreateConfigService $createConfigService,
        private AuthorisationService $authorisationService,
    ) { }
    
    /**
     * Get the configuration for the entity create page.
     * 
     * @param Request $request
     * @param string $entityCode
     *
     * @return JsonResponse
    */
    public function getConfig(Request $request, string $entityCode) : JsonResponse
    {
        $entity = $this->entityFactory->create($entityCode);
        $this->authorisationService->canCreate($request->user(), $entity, true);
        $viewConfig = $this->createConfigService->getViewConfig($entity);
        
        return response()->json($viewConfig);
    }
}
