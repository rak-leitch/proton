<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\EntityDefinitions;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;
use Adepta\Proton\Tests\Models\Task as TaskModel;
use Adepta\Proton\Field\Id;
use Adepta\Proton\Field\Text;

class TaskDefinition implements EntityDefinitionContract
{
    /**
     * Constructor
     * 
     * @param EntityConfigContract $entityConfig
    */
    public function __construct(
        private EntityConfigContract $entityConfig
    ) { }
    
    /**
     * Define and return the entity's configuration.
     * 
     * @return EntityConfigContract $entityConfig
    */
    public function getEntityConfig() : EntityConfigContract
    {
        $this->entityConfig
            ->setCode('task')
            ->setModel(TaskModel::class)
            ->addField(Id::create('id')->sortable())
            ->addField(Text::create('project_id')->sortable()) //TODO: Needs to be a relationship type later
            ->addField(Text::create('name')->sortable())
            ->addField(Text::create('description'));
            
        return $this->entityConfig;
    }
}
