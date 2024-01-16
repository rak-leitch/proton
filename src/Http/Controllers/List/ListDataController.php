<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers\List;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\List\ListDataService;

class ListDataController extends BaseController
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param ListDataService $listDataService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private ListDataService $listDataService,
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
        $listData = $this->listDataService->getData($entity, $page, $itemsPerPage, $sortBy);
        
        return response()->json($listData);
    }
}
