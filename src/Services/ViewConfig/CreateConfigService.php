<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\ViewConfig;

use Adepta\Proton\Entity\Entity;

final class CreateConfigService
{    
    /**
     * Get the create page config for an entity
     * for use by the frontend.
     *
     * @param Entity $entity
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
        $pageConfig['title'] = 'New '.$entity->getLabel();
        
        return $pageConfig;
    }
}
