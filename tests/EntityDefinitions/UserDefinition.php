<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\EntityDefinitions;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;
use Adepta\Proton\Field\Id;
use Adepta\Proton\Field\Text;
use Adepta\Proton\Field\HasMany;
use Adepta\Proton\Tests\Models\User as UserModel;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


class UserDefinition implements EntityDefinitionContract
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
            ->setCode('user')
            ->setModel(UserModel::class)
            ->addField(Id::create('id')->sortable())
            ->addField(HasMany::create('project'))
            ->addField(Text::create('name')->sortable()->setValidation('required')->name())
            ->setQueryFilter(function(Builder $query) {
                /** @var UserModel $user */
                $user = Auth::user();
                if(!$user->is_admin) {
                    $query->where('id', $user->id);
                }
            });
        
        return $this->entityConfig;
    }
}
