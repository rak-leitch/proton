<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\Form;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\Form\FormConfigService;
use Adepta\Proton\Services\Auth\AuthorisationService;

class FormConfigController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param FormConfigService $formConfigService
     * @param AuthorisationService $authorisationService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private FormConfigService $formConfigService,
        private AuthorisationService $authorisationService,
    ) { }
    
    /**
     * Get the configuration for an form component.
     * 
     * @param Request $request
     * @param string $entityCode
     * @param int $entityId
     *
     * @return JsonResponse
    */
    public function getConfig(Request $request, string $entityCode, int $entityId) : JsonResponse
    {
        $formConfig = [];
        $entity = $this->entityFactory->create($entityCode);
        $modelClass = $entity->getModel();
        $model = $modelClass::findOrFail($entityId);
        $this->authorisationService->canUpdate($request->user(), $model, true);
        $formConfig = $this->formConfigService->getFormConfig($entity, $model);
        
        return response()->json($formConfig);
    }
}
