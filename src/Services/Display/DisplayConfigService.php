<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Display;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use ReflectionClass;
use Adepta\Proton\Field\BelongsTo;
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
     * @return mixed[]
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
            $fieldConfig['title'] = $fieldName;
            $fieldConfig['key'] = $fieldName;
            $fieldConfig['frontend_type'] = $field->getFrontendType(DisplayContext::VIEW);
            $fieldConfig['value'] = $field->getProcessedValue($model);
            
            $displayConfig['fields'][] = $fieldConfig;
        };
        
        return $displayConfig;
    }
}
