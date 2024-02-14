<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\List;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\List\ListDataService;
use Adepta\Proton\Services\Auth\AuthorisationService;

final class ListDataController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param ListDataService $listDataService
     * @param AuthorisationService $authorisationService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private ListDataService $listDataService,
        private AuthorisationService $authorisationService,
    ) { }
    
    /**
     * Get the data for an list component.
     * 
     * @param Request $request
     * @param string $entityCode
     * @param int $page 
     * @param int $itemsPerPage 
     * @param string $sortBy
     *
     * @return JsonResponse
    */
    public function getData(
        Request $request, 
        string $entityCode,
        int $page,
        int $itemsPerPage,
        string $sortBy
    ) : JsonResponse
    {
        $listData = [];
        $entity = $this->entityFactory->create($entityCode);
        $this->authorisationService->canViewAny($request->user(), $entity, true);
        [$contextCode, $contextId] = $this->getContext($request);

        $listData = $this->listDataService->getData(
            entity: $entity,
            user: $request->user(), 
            page: $page, 
            itemsPerPage: $itemsPerPage, 
            sortBy: $sortBy, 
            contextCode: $contextCode, 
            contextId: (int)$contextId
        );
        
        return response()->json($listData);
    }
    
    /**
     * Get the context from the query string. Note that
     * the query() function can potentially return an array.
     * 
     * @param Request $request
     *
     * @return array<string|null>
    */
    private function getContext($request)
    {
        $contextCode = null;
        $contextId = null;
        $requestCode = $request->query('contextCode');
        $requestId = $request->query('contextId');
        
        if(is_string($requestCode) && is_string($requestId)) {
            $contextCode = $requestCode;
            $contextId = $requestId;
        }
        
        return [
            $contextCode,
            $contextId,
        ];
    }
}
