<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\ViewConfig;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;

final class UpdateConfigService
{    
    /**
     * Get the index page config for an entity
     * for use by the frontend.
     *
     * @param Entity $entity
     * @param Model $model
     * 
     * @return mixed[]
    */
    public function getViewConfig(Entity $entity, Model $model) : array
    {
        $pageConfig = [];
        $primaryKeyName = $entity->getPrimaryKeyField()->getFieldName();
        
        $pageConfig['entity_code'] = $entity->getCode();
        $pageConfig['entity_id'] = $model->{$primaryKeyName};
        $pageConfig['title'] = 'Update '.$entity->getLabel();
        
        return $pageConfig;
    }
}
