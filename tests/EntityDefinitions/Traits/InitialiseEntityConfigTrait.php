<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\EntityDefinitions\Traits;

use Illuminate\Foundation\Application;
use Adepta\Proton\Tests\EntityDefinitions\UserDefinition;
use Adepta\Proton\Tests\EntityDefinitions\ProjectDefinition;
use Adepta\Proton\Tests\EntityDefinitions\TaskDefinition;

trait InitialiseEntityConfigTrait
{
    /**
     * Set up the test entity config.
     * 
     * @param Application $app
     *
     * @return void
    */
    public function setEntityConfig(Application $app)
    {
        $app['config']->set('proton.definition_classes.user', UserDefinition::class);
        $app['config']->set('proton.definition_classes.project', ProjectDefinition::class);
        $app['config']->set('proton.definition_classes.task', TaskDefinition::class);
    }
}
