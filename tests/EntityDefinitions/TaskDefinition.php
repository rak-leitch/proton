<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\EntityDefinitions;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;
use Adepta\Proton\Tests\Models\Task as TaskModel;
use Adepta\Proton\Field\Id;
use Adepta\Proton\Field\Text;
use Adepta\Proton\Field\BelongsTo;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
            ->addField(BelongsTo::create('project')->setValidation('required'))
            ->addField(Text::create('name')->sortable()->setValidation('required')->name())
            ->addField(Text::create('description'))
            ->setQueryFilter(function(Builder $query) {
                /** @var \Adepta\Proton\Tests\Models\User $user */
                $user = Auth::user();
                if(!$user->is_admin) {
                    $query->whereRelation('project.user', 'id', $user->id);
                }
            });
            
        return $this->entityConfig;
    }
}
