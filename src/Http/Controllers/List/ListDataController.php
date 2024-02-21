<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\List;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\List\ListDataService;
use Adepta\Proton\Services\Auth\AuthorisationService;
use StdClass;

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
     *
     * @return JsonResponse
    */
    public function getData(
        Request $request, 
        string $entityCode,
        int $page,
        int $itemsPerPage
    ) : JsonResponse
    {
        $listData = [];
        $entity = $this->entityFactory->create($entityCode);
        $this->authorisationService->canViewAny($request->user(), $entity, true);
        $requestQuery = $this->getRequestQuery($request);

        $listData = $this->listDataService->getData(
            $entity,
            $request->user(), 
            $page, 
            $itemsPerPage, 
            $requestQuery
        );
        
        return response()->json($listData);
    }
    
    /**
     * Get the values from the query string. Note that
     * the query() function can potentially return an array.
     * 
     * @param Request $request
     *
     * @return StdClass
    */
    private function getRequestQuery($request) : StdClass
    {
        $requestQuery = [];
        
        $queryKeys = [
            'contextCode',
            'contextId',
            'sortField',
            'sortOrder'
        ];
        
        foreach($queryKeys as $queryKey) {
            $value = $request->query($queryKey);
            $requestQuery[$queryKey] = is_string($value) ? $value : null;
        }
        
        return (object)$requestQuery;
    }
}
