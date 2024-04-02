<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\ViewConfig;

use Adepta\Proton\Entity\Entity;

final class IndexConfigService
{    
    /**
     * Get the index page config for an entity
     * for use by the frontend.
     *
     * @aparam Entity $entity
     * 
     * @return array{
     *     entityCode: string, 
     *     title: string
     * }
    */
    public function getViewConfig(Entity $entity) : array
    {
        $pageConfig = [];
        
        $pageConfig['entityCode'] = $entity->getCode();
        $pageConfig['title'] = $entity->getLabel(true);

        return $pageConfig;
    }
}
