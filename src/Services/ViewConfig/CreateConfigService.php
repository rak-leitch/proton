<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\ViewConfig;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;

final class CreateConfigService
{    
    /**
     * Get the create page config for an entity
     * for use by the frontend.
     *
     * @param Entity $entity
     * 
     * @return array{
     *     entity_code: string, 
     *     title: string
     * }
    */
    public function getViewConfig(Entity $entity) : array
    {
        $pageConfig = [];
        
        $pageConfig['entity_code'] = $entity->getCode();
        $pageConfig['title'] = 'New '.$entity->getLabel();
        
        return $pageConfig;
    }
}
