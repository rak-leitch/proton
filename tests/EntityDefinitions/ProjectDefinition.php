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
            ->addField(BelongsTo::create('user')->validation('required'))
            ->addField(HasMany::create('task'))
            ->addField(Text::create('name')->sortable()->validation('required')->name())
            ->addField(Text::create('description')->title('Project Description'))
            ->addField(Text::create('priority')->sortable()->validation('required'))
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
