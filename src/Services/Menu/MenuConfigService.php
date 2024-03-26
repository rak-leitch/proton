<?php declare(strict_types = 1);

namespace Adepta\Proton\Services\Menu;

use Adepta\Proton\Entity\EntityFactory;
use Adepta\Proton\Services\Auth\AuthorisationService;
use Adepta\Proton\Services\ConfigStoreService;
use Illuminate\Foundation\Auth\User;

final class MenuConfigService
{    
    /**
     * Constructor.
     *
     * @param EntityFactory $entityFactory
     * @param AuthorisationService $authorisationService
     * @param ConfigStoreService $configStoreService
    */
    public function __construct(
        private EntityFactory $entityFactory,
        private AuthorisationService $authorisationService,
        private ConfigStoreService $configStoreService
    ) { }
    
    /**
     * Get the entity menu config
     *
     * @param ?User $user
     * 
     * @return array{
     *     entities: array<int, array{
     *         entityCode: string, 
     *         label: string
     *     }>
     * }
    */
    public function getMenuConfig(?User $user) : array
    {
        $config = [];
        $config['entities'] = [];
        $entityCodes = $this->configStoreService->getAllEntityCodes();
        
        foreach($entityCodes as $entityCode) {
            $entity = $this->entityFactory->create($entityCode);
            if($this->authorisationService->canViewAny($user, $entity)) {
                $config['entities'][] = [
                    'entityCode' => $entity->getCode(),
                    'label' => $entity->getLabel(true),
                ];
            }
        }
        
        return $config;
    }
}
