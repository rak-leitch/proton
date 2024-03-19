<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\EntityDefinitions;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;
use Adepta\Proton\Tests\Models\Project as ProjectModel;
use Adepta\Proton\Field\Config\Id;
use Adepta\Proton\Field\Config\Text;
use Adepta\Proton\Field\Config\HasMany;
use Adepta\Proton\Field\Config\BelongsTo;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProjectDefinition implements EntityDefinitionContract
{    
    /**
     * Define and return the entity's configuration.
     * 
     * @param EntityConfigContract $entityConfig
     * 
     * @return EntityConfigContract
    */
    public function getEntityConfig(EntityConfigContract $entityConfig) : EntityConfigContract
    {
        $entityConfig
            ->setModel(ProjectModel::class)
            ->addField(Id::create('id')->sortable())
            ->addField(BelongsTo::create('user')->setValidation('required'))
            ->addField(HasMany::create('task'))
            ->addField(Text::create('name')->sortable()->setValidation('required')->name())
            ->addField(Text::create('description'))
            ->addField(Text::create('priority')->sortable()->setValidation('required'))
            ->setQueryFilter(function(Builder $query) {
                /** @var \Adepta\Proton\Tests\Models\User $user */
                $user = Auth::user();
                if(!$user->is_admin) {
                    $query->whereRelation('user', 'id', $user->id);
                }
            });
        
        return $entityConfig;
    }
}
