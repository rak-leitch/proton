<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\List;

use Adepta\Proton\Entity\Entity;

class ListDataService
{    
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
        $modelClass = $entity->getModel();
        $totalRows = $modelClass::count();
        $skip = ($page - 1) * $itemsPerPage;
        $collection = $modelClass::skip($skip)->take($itemsPerPage)->get();
        
        foreach($collection as $model) {
            
            $row = [];
            
            foreach($entity->getFields() as $field) {
                $fieldName = $field->getFieldName();
                $row[$fieldName] = $model->{$fieldName};
            };
            
            $data[] = $row;
        }
        
        $listData['totalRows'] = $totalRows;
        $listData['data'] = $data;
        
        return $listData;
    }
}
