<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\View;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Entity\EntityFactory;
use Adepta\Proton\Services\ViewConfig\IndexConfigService;
use Adepta\Proton\Services\Auth\AuthorisationService;

final class EntityIndexController extends BaseController
{
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param IndexConfigService $indexConfigService
     * @param AuthorisationService $authorisationService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private IndexConfigService $indexConfigService,
        private AuthorisationService $authorisationService,
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
        $viewConfig = [];
        $entity = $this->entityFactory->create($entityCode);
        $this->authorisationService->canViewAny($request->user(), $entity, true);
        
        $viewConfig = $this->indexConfigService->getViewConfig($entity);
        
        return response()->json($viewConfig);
    }
}
