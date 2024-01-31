<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\Form;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\Form\FormModelFactory;
use Adepta\Proton\Services\Form\FormSubmitService;
use Adepta\Proton\Services\Form\FormRulesService;

class FormSubmitController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param FormModelFactory $formModelFactory
     * @param FormSubmitService $formSubmitService
     * @param FormRulesService $formRulesService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private FormModelFactory $formModelFactory,
        private FormSubmitService $formSubmitService,
        private FormRulesService $formRulesService,
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
    public function submit(Request $request, string $entityCode, int $entityId) : JsonResponse
    {
        $entity = $this->entityFactory->create($entityCode);
        $model = $this->formModelFactory->getUpdateModel($entity, $entityId, $request->user());
        $validatedData = $request->validate($this->formRulesService->getRules($entity));
        $result = $this->formSubmitService->submit($entity, $model, $validatedData);
        
        return response()->json($result);
    }
}
