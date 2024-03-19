<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\EntityDefinitions;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;
use Adepta\Proton\Tests\Models\Project as ProjectModel;
use Adepta\Proton\Field\Id;
use Adepta\Proton\Field\Text;
use Adepta\Proton\Field\HasMany;
use Adepta\Proton\Field\BelongsTo;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProjectDefinition implements EntityDefinitionContract
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
            ->setCode('project')
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
        
        return $this->entityConfig;
    }
}
