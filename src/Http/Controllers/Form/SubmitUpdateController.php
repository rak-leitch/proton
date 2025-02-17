<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\Form;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Entity\EntityFactory;
use Adepta\Proton\Services\Form\FormModelFactory;
use Adepta\Proton\Services\Form\FormSubmitService;
use Adepta\Proton\Services\Form\FormValidationService;
use Adepta\Proton\Field\DisplayContext;

final class SubmitUpdateController extends BaseController
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
     * Handle the submission of an update form.
     * 
     * @param Request $request
     * @param string $entityCode
     * @param int|string $entityId
     *
     * @return JsonResponse
    */
    public function submit(Request $request, string $entityCode, int|string $entityId) : JsonResponse
    {
        $entity = $this->entityFactory->create($entityCode);
        $model = $this->formModelFactory->getUpdateModel($entity, $entityId, $request->user());
        $validatedData = $request->validate($this->formValidationService->getRules(DisplayContext::UPDATE, $entity));
        
        $this->formSubmitService->submit(
            $request->user(),
            DisplayContext::UPDATE, 
            $entity, 
            $model, 
            $validatedData
        );
        
        return response()->json([]);
    }
}
