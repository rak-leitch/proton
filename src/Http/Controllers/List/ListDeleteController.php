<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\List;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Entity\EntityFactory;
use Adepta\Proton\Services\Auth\AuthorisationService;
use StdClass;

final class ListDeleteController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param AuthorisationService $authorisationService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private AuthorisationService $authorisationService,
    ) { }
    
    /**
     * Handle an entity delete request.
     * 
     * @param Request $request
     * @param string $entityCode
     * @param int|string $entityId
     *
     * @return JsonResponse
    */
    public function delete(
        Request $request, 
        string $entityCode,
        int|string $entityId
    ) : JsonResponse
    {
        $entity = $this->entityFactory->create($entityCode);
        $model = $entity->getLoadedModel($entityId);
        $this->authorisationService->canForceDelete($request->user(), $model, true);
        $model->delete();
        return response()->json([]);
    }
}
