# Proton

Proton is a Laravel package which provides a configurable CRUD SPA based on Eloquent ORM relationships and Vuetify, in a similar fashion to Laravel Nova. It's not yet ready for real-world use, but it currently implements display, creation, updating and deletion of database entities, which can be related by the BelongsTo and HasMany Eloquent relationships. 

## Installation

At the moment, you'll need to install the package locally alongside an installation of Laravel 10.x or 11.x. Start by cloning the repo to a location accessible by your Laravel installation:

```
git clone https://adepta2@bitbucket.org/adepta-proton/proton.git
```

In your Laravel installation's `composer.json`, add repositories config so that composer looks in the correct place for the package, e.g.

```
"repositories": [
    {
        "type": "path",
        "url": "../proton"
    }
],
```

You will also have to change the `minimum-stability` entry in Laravel's `composer.json` to `dev`.

If you're using Laravel Sail, then you'll need to ensure that this path is listed in the volumes config for the `laravel.test` service:

```bash
volumes:
    - '../proton:/var/www/proton'
```

You can now go ahead and require the package:

```
composer require adepta/proton
```

Finally, to publish the frontend assets, run this command in the Laravel installation:

```
php artisan vendor:publish --provider="Adepta\Proton\ProtonServiceProvider" --tag=assets
```

## Usage

### Configuration

Proton uses entity definition classes defined in Laravel's `config/proton.php` file to configure each entity. The contents of the `config/proton.php` file might look something like this (`definition_classes` array keys are __snake case__ entity codes):

```php
<?php declare(strict_types = 1);

use App\Proton\ProjectDefinition;
use App\Proton\TaskDefinition;
use App\Proton\UserDefinition;

return [
    'definition_classes' => [
        'project' => ProjectDefinition::class,
        'task' => TaskDefinition::class,
        'user' => UserDefinition::class,
    ]
];
```

Here's an example of an entity definition class:

```php
<?php declare(strict_types = 1);

namespace App\Proton;

use Adepta\Proton\Contracts\Entity\EntityConfigContract;
use Adepta\Proton\Contracts\Entity\EntityDefinitionContract;
use Adepta\Proton\Tests\Models\Project as ProjectModel;
use Adepta\Proton\Field\Config\Id;
use Adepta\Proton\Field\Config\Text;
use Adepta\Proton\Field\Config\TextArea;
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
            ->setModelClass(ProjectModel::class)
            ->addField(Id::create('id')->sortable())
            ->addField(BelongsTo::create('user')->validation('required'))
            ->addField(HasMany::create('task'))
            ->addField(Text::create('name')->sortable()->validation('required')->name())
            ->addField(TextArea::create('description')->title('Project Description'))
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
```

Note that the definition class must implement the `EntityDefinitionContract` and sets the configuration on an `EntityConfigContract` which is then returned.

This configuration comprises a reference to the Eloquent model the entity definition (in this case `project`) is associated with and a number of field definitions, each corresponding to a column in the model's DB table, along with any form validation required etc. Each field defined here will appear in the various widgets on the frontend.

Each entity definition must contain exactly one `name()` field. Finally, `setQueryFilter()` allows restriction of records displayed in lists, usually via user filtering.

For a full example, see the `EntityDefinitions`, `Database/Migrations`, `Models` and `Policies` directories under the `tests` directory in the package.

Note that all Proton routes currently require a logged in user and a Policy must be implemented for all Models used by entity definitions. Policy methods currently used by Proton are `viewAny()`, `view()`, ` create()`, `update()`, and `forceDelete()`. In addition, there is a `add<entity_code>()` method; see the UI Update section for details.

### Resulting UI

There are four main views (pages):

#### Index

![Proton index view](https://filedn.com/lghqsCeu3YOjgUIe8IJkOjy/proton_screenshots/index.png)

For each entity definition, the index displaying all records will be available at `/proton/entity/<entity_code>/index`. The results are filtered through the query filter in the entity definition class if provided. From the list displayed here, it is possible to edit, view or delete each record or to create a new record. 

The 'View Entities' menu in the top right of the screen in every view will have an entry for each entity definition that links to the index page for it, as long as the user has `viewAny` Policy authorization for the entity's corresponding model. Note that a CRUD button on the list will not appear if any of the respective `create`/`update`/`delete`/`view` Policy methods disallow this for a particular user/record.

#### Create

![Proton create view](https://filedn.com/lghqsCeu3YOjgUIe8IJkOjy/proton_screenshots/create.png)

When the 'New' button is clicked in an entity's list, this view will allow the user to create a new record. The validation set up in the entity definition will be applied as in the screenshot. Once the form is submitted successfully the app will return to the previous view.

#### Update

![Proton update view](https://filedn.com/lghqsCeu3YOjgUIe8IJkOjy/proton_screenshots/update.png)

Similar to the Create view, form validation is applied here. Note the `BelongsTo` select options (`User` field in the screenshot) are subject to the query filter in the parent entity's definition and also that when the form is submitted, the parent's Policy `add()` method is checked, in this case `UserPolicy::addProject()` - see the package test file at `\Adepta\Proton\Tests\Policies\UserPolicy` for an example.

#### Display

![Proton update view](https://filedn.com/lghqsCeu3YOjgUIe8IJkOjy/proton_screenshots/display.png)

This view is available at `/proton/entity/<entity_code>/display/<entity_id>`. Basic record information is available at the top of the view and a number of lists appear below, one for each `HasMany` relation defined in the current record's entity definition. Within each list the results are filtered not only by the child entity's query filter, but also by the `BelongsTo` field value corresponding to the currently displayed main entity, so that only relevant records are displayed.

CRUD buttons are displayed as in the index view subject to Policy constraints.

## Limitations

- Currently, standard Laravel conventions are assumed with model relationship names, `BelongsTo` (foreign key) fields etc.
- Only the `BelongsTo` and `HasMany` Eloquent relationships are currently handled.
- Only `ID`, `Text` and `TextArea`, `BelongsTo` and `HasMany` fields are supported. 
- Soft deletes are not yet supported.

## Licence

[MIT](https://choosealicense.com/licenses/mit/)