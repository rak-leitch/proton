<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\ViewConfig;

use Adepta\Proton\Entity\Entity;

class IndexConfigService
{    
    /**
     * Get the page config for an entity
     * for use by the frontend.
     *
     * @aparam Entity $entity
     * 
     * @return mixed[]
    */
    public function getViewConfig(Entity $entity) : array
    {
        $pageConfig = [];
        
        $pageConfig['entity_code'] = $entity->getCode();
        $pageConfig['entity_label_plural'] = $entity->getLabel(true);
        
        return $pageConfig;
    }
}
