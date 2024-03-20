<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\View;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Entity\EntityFactory;
use Adepta\Proton\Services\Form\FormModelFactory;
use Adepta\Proton\Services\ViewConfig\UpdateConfigService;

final class EntityUpdateController extends BaseController
{
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param FormModelFactory $formModelFactory
     * @param UpdateConfigService $updateConfigService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private FormModelFactory $formModelFactory,
        private UpdateConfigService $updateConfigService,
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
        $entity = $this->entityFactory->create($entityCode);
        $model = $this->formModelFactory->getUpdateModel($entity, $entityId, $request->user());
        $viewConfig = $this->updateConfigService->getViewConfig($entity, $model);
        
        return response()->json($viewConfig);
    }
}
