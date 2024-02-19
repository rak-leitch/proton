<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\ViewConfig;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Contracts\Field\FieldContract;
use Adepta\Proton\Field\HasMany;
use Adepta\Proton\Services\EntityFactory;
use Adepta\Proton\Services\Auth\AuthorisationService;
use Illuminate\Foundation\Auth\User;

final class DisplayConfigService
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param AuthorisationService $authorisationService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private AuthorisationService $authorisationService,
    ) { }
    
    /**
     * Get the view config for an entity instance
     * for use by the frontend.
     *
     * @param ?User $user
     * @param Entity $parentEntity
     * @param Model $model
     * 
     * @return mixed[]
    */
    public function getViewConfig(
        ?User $user,
        Entity $parentEntity,
        Model $model
        
    ) : array
    {
        $viewConfig = [];
        $viewConfig['title'] = $parentEntity->getLabel();
        $viewConfig['lists'] = [];
        $parentKey = $parentEntity->getPrimaryKeyField()->getFieldName();
        $parentId = $model->{$parentKey};
        
        $viewConfig['displaySettings'] = [
            'entityCode' => $parentEntity->getCode(),
            'entityId' => $parentId,
        ];
        
        foreach($parentEntity->getFields(
            displayContext: DisplayContext::VIEW, 
            fieldTypes: collect([HasMany::class]),
            onlyDisplayable: false
        ) as $field) {
            $listEntity = $this->entityFactory->create($field->getSnakeName());
            if($this->authorisationService->canViewAny($user, $listEntity)) {
                $viewConfig['lists'][] = [
                    'title' => $listEntity->getLabel(true),
                    'listSettings' => [
                        'entityCode' => $listEntity->getCode(),
                        'contextCode' => $parentEntity->getCode(),
                        'contextId' => $parentId
                    ]
                ]; 
            }
        };
        
        return $viewConfig;
    }
}
