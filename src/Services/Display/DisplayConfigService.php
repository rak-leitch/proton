<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Display;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;

final class DisplayConfigService
{
    /**
     * Get the form config for an entity instance
     * for use by the frontend.
     *
     * @param Entity $entity
     * @param Model $model
     * 
     * @return array{
     *     fields: array<int, array{
     *         title: string, 
     *         key: string, 
     *         frontendType: string, 
     *         value: float|int|string|bool|null
     *     }>
     * }
    */
    public function getDisplayConfig(Entity $entity, Model $model) : array
    {
        $displayConfig = [];
        $displayConfig['fields'] = [];
        
        $fields = $entity->getFields(
            displayContext: DisplayContext::DISPLAY,
        );
        
        foreach($fields as $field) {
            $fieldConfig = [];
            $fieldName = $field->getFieldName();
            $fieldConfig['title'] = $field->getTitle();
            $fieldConfig['key'] = $fieldName;
            $fieldConfig['frontendType'] = $field->getFrontendType(DisplayContext::DISPLAY)->value;
            $fieldConfig['value'] = $field->getProcessedValue($model);
            
            $displayConfig['fields'][] = $fieldConfig;
        };
        
        return $displayConfig;
    }
}
