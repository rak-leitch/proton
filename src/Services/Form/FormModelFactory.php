<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Form;

use Adepta\Proton\Services\Auth\AuthorisationService;
use Adepta\Proton\Entity\Entity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

final class FormModelFactory
{    
    /**
     * Constructor.
     * 
     * @param AuthorisationService $authorisationService
     * 
    */
    public function __construct(
        private AuthorisationService $authorisationService
    ) { }
    
    /**
     * Create the model for a form update.
     *
     * @param Entity $entity
     * @param int|string $entityId
     * @param ?Authenticatable $user
     * 
     * @return Model
    */
    public function getUpdateModel(Entity $entity, int|string $entityId, ?Authenticatable $user) : Model
    {
        $model = $entity->getLoadedModel($entityId);
        $this->authorisationService->canUpdate($user, $model, true);
        return $model;
    }
    
    /**
     * Create the model for a form create.
     *
     * @param Entity $entity
     * @param Authenticatable $user
     * 
     * @return Model
    */
    public function getCreateModel(Entity $entity, ?Authenticatable $user) : Model
    {
        $modelClass = $entity->getModelClass();
        $this->authorisationService->canCreate($user, $entity, true);
        return $modelClass::make();
    }
}
