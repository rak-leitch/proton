<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\ViewConfig;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Contracts\Field\FieldContract;
use Adepta\Proton\Field\HasMany;
use Adepta\Proton\Services\EntityFactory;

final class ViewConfigService
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
    */
    public function __construct(
        private EntityFactory $entityFactory,
    ) { }
    
    /**
     * Get the view config for an entity instance
     * for use by the frontend.
     *
     * @param Entity $parentEntity
     * @param Model $model
     * 
     * @return mixed[]
    */
    public function getViewConfig(
        Entity $parentEntity,
        Model $model
        
    ) : array
    {
        $viewConfig = [];
        $viewConfig['title'] = $parentEntity->getLabel();
        $viewConfig['lists'] = [];
        $parentKey = $parentEntity->getPrimaryKeyField()->getFieldName();
        $parentId = $model->{$parentKey};
        
        foreach($parentEntity->getFields(DisplayContext::VIEW, collect([HasMany::class])) as $field) {
            $listEntity = $this->entityFactory->create($field->getSnakeName());
            $viewConfig['lists'][] = [
                'title' => $listEntity->getLabel(true),
                'listSettings' => [
                    'entityCode' => $listEntity->getCode(),
                    'contextCode' => $parentEntity->getCode(),
                    'contextId' => $parentId
                ]
            ]; 
        };
        
        return $viewConfig;
    }
}
