<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests;

use Adepta\Proton\Tests\EntityDefinitions\ProjectDefinition;
use Adepta\Proton\Tests\EntityDefinitions\TaskDefinition;

class TestConfig
{
    /**
     * Get the proton configuration for testing.
     *
     * @return array<string, array<string, string>>
    */   
    public static function getConfig() : array
    {
        $config = [];
        
        $config['definition_classes'] = [
            'project' => ProjectDefinition::class,
            'task' => TaskDefinition::class,
        ];
        
        return $config;
    }
}
