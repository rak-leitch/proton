<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\EntityDefinitions;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;
use Adepta\Proton\Tests\Models\Task as TaskModel;
use Adepta\Proton\Field\Config\Id;
use Adepta\Proton\Field\Config\Text;
use Adepta\Proton\Field\Config\TextArea;
use Adepta\Proton\Field\Config\BelongsTo;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TaskDefinition implements EntityDefinitionContract
{
    /**
     * Define and return the entity's configuration.
     * 
     * @param EntityConfigContract $entityConfig
     * 
     * @return EntityConfigContract $entityConfig
    */
    public function getEntityConfig(EntityConfigContract $entityConfig) : EntityConfigContract
    {
        $entityConfig
            ->setModelClass(TaskModel::class)
            ->addField(Id::create('id')->sortable())
            ->addField(BelongsTo::create('project')->validation('required'))
            ->addField(Text::create('name')->sortable()->validation('required')->name())
            ->addField(TextArea::create('description'))
            ->setQueryFilter(function(Builder $query) {
                /** @var \Adepta\Proton\Tests\Models\User $user */
                $user = Auth::user();
                if(!$user->is_admin) {
                    $query->whereRelation('project.user', 'id', $user->id);
                }
            });
            
        return $entityConfig;
    }
}
