<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\ViewConfig;

use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Adepta\Proton\Field\DisplayContext;
use Adepta\Proton\Field\Internal\HasMany;
use Adepta\Proton\Entity\EntityFactory;
use Adepta\Proton\Services\Auth\AuthorisationService;
use Illuminate\Contracts\Auth\Authenticatable;

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
     * @param ?Authenticatable $user
     * @param Entity $parentEntity
     * @param Model $model
     * 
     * @return array{
     *     title: string, 
     *     lists: array<int, array{
     *         title: string,  
     *         listSettings: array{
     *             entityCode: string, 
     *             contextCode: string, 
     *             contextId: int|string
     *          }
     *      }>, 
     *      displaySettings: array{
     *          entityCode: string, 
     *          entityId: int|string
     *      }
     *  }
    */
    public function getViewConfig(
        ?Authenticatable $user,
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
            displayContext: DisplayContext::DISPLAY, 
            fieldTypes: collect([HasMany::class]),
            onlyDisplayable: false
        ) as $field) {
            $listEntity = $this->entityFactory->create($field->getRelatedEntityCode());
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
