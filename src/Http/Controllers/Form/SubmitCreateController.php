<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\Form;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\Form\FormModelFactory;
use Adepta\Proton\Services\Form\FormSubmitService;
use Adepta\Proton\Services\Form\FormValidationService;
use Adepta\Proton\Field\DisplayContext;

final class SubmitCreateController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param FormModelFactory $formModelFactory
     * @param FormSubmitService $formSubmitService
     * @param FormValidationService $formValidationService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private FormModelFactory $formModelFactory,
        private FormSubmitService $formSubmitService,
        private FormValidationService $formValidationService,
    ) { }
    
    /**
     * Get the configuration for an form component.
     * 
     * @param Request $request
     * @param string $entityCode
     *
     * @return JsonResponse
    */
    public function submit(Request $request, string $entityCode) : JsonResponse
    {
        $entity = $this->entityFactory->create($entityCode);
        $model = $this->formModelFactory->getCreateModel($entity, $request->user());
        $validatedData = $request->validate($this->formValidationService->getRules(DisplayContext::CREATE, $entity));
        $this->formSubmitService->submit(DisplayContext::CREATE, $entity, $model, $validatedData);
        
        return response()->json([]);
    }
}
