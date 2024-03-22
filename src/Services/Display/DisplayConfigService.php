<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Display;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use ReflectionClass;
use Adepta\Proton\Field\Internal\BelongsTo;
use Adepta\Proton\Exceptions\ConfigurationException;

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
     *         frontend_type: string, 
     *         value: float|int|string|null
     *     }>
     * }
    */
    public function getDisplayConfig(Entity $entity, Model $model) : array
    {
        $displayConfig = [];
        $displayConfig['fields'] = [];
        
        $fields = $entity->getFields(
            displayContext: DisplayContext::VIEW,
        );
        
        foreach($fields as $field) {
            $fieldConfig = [];
            $fieldName = $field->getFieldName();
            $fieldConfig['title'] = $field->getTitle();
            $fieldConfig['key'] = $fieldName;
            $fieldConfig['frontend_type'] = $field->getFrontendType(DisplayContext::VIEW)->value;
            $fieldConfig['value'] = $field->getProcessedValue($model);
            
            $displayConfig['fields'][] = $fieldConfig;
        };
        
        return $displayConfig;
    }
}
