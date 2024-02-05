<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\List;

use Adepta\Proton\Entity\Entity;
use Illuminate\Support\Facades\Auth;
use Adepta\Proton\Services\Auth\AuthorisationService;
use Adepta\Proton\Field\DisplayContext;

class ListDataService
{    
    /**
     * Constructor.
     * 
     * @param AuthorisationService $authService
    */
    public function __construct(
        private AuthorisationService $authService,
    ) { }
    
    /**
     * Get the list data for an entity
     * for use by the frontend.
     *
     * @param Entity $entity 
     * @param int $page 
     * @param int $itemsPerPage 
     * @param string $sortBy
     * 
     * @return mixed[]
    */
    public function getData(
        Entity $entity, 
        int $page, 
        int $itemsPerPage, 
        string $sortBy
    ) : array
    {
        $data = [];
        $permissions = [];
        $modelClass = $entity->getModel();
        $totalRows = $modelClass::count();
        $skip = ($page - 1) * $itemsPerPage;
        $collection = $modelClass::skip($skip)->take($itemsPerPage)->get();
        $user = Auth::user();
        
        foreach($collection as $model) {
            
            $row = [];
            
            //TODO: Need indication of view/index context here?
            foreach($entity->getFields(DisplayContext::INDEX) as $field) {
                $fieldName = $field->getFieldName();
                $row[$fieldName] = $model->{$fieldName};
            };
            
            //TODO: Can't assume pk
            $permissions[$model->getKey()] = [
                'update' => $this->authService->canUpdate($user, $model),
                'view' => $this->authService->canView($user, $model),
                'delete' => $this->authService->canDelete($user, $model),
            ];
            
            $data[] = $row;
        }
        
        $listData['totalRows'] = $totalRows;
        $listData['data'] = $data;
        $listData['permissions'] = $permissions;
        
        return $listData;
    }
}
