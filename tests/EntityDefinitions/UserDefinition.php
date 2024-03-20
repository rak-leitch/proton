<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\EntityDefinitions;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;
use Adepta\Proton\Field\Config\Id;
use Adepta\Proton\Field\Config\Text;
use Adepta\Proton\Field\Config\HasMany;
use Adepta\Proton\Tests\Models\User as UserModel;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


class UserDefinition implements EntityDefinitionContract
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
            ->setModel(UserModel::class)
            ->addField(Id::create('id')->sortable())
            ->addField(HasMany::create('project'))
            ->addField(Text::create('name')->sortable()->validation('required')->name())
            ->setQueryFilter(function(Builder $query) {
                /** @var UserModel $user */
                $user = Auth::user();
                if(!$user->is_admin) {
                    $query->where('id', $user->id);
                }
            });
        
        return $entityConfig;
    }
}
