<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\Form;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\Form\FormModelFactory;
use Adepta\Proton\Services\Form\FormConfigService;

class FormConfigController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param FormModelFactory $formModelFactory
     * @param FormConfigService $formConfigService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private FormModelFactory $formModelFactory,
        private FormConfigService $formConfigService,
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
        $entity = $this->entityFactory->create($entityCode);
        $model = $this->formModelFactory->getUpdateModel($entity, $entityId, $request->user());
        $formConfig = $this->formConfigService->getFormConfig($entity, $model);
        
        return response()->json($formConfig);
    }
}
