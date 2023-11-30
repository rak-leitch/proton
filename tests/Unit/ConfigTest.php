<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Unit;

use Adepta\Proton\Tests\TestCase;
use Adepta\Proton\Contracts\ConfigStoreContract;
use Adepta\Proton\Tests\EntityDefinitions\ProjectDefinition;
use Adepta\Proton\Tests\EntityDefinitions\TaskDefinition;
use Adepta\Proton\Exceptions\ConfigurationException;

class ConfigTest extends TestCase
{
    private ConfigStoreContract $configService;
    
    /**
     * Setup for eash test below.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->configService = app()->make(ConfigStoreContract::class);
    }
    
    /**
     * Check project definition config is correct.
     *
     * @return void
    */
    public function test_project_definition_config() : void
    {        
        $this->assertEquals( 
            ProjectDefinition::class, 
            $this->configService->getDefinitionClass('project')
        ); 
    }
    
    /**
     * Check task definition config is correct.
     *
     * @return void
    */
    public function test_task_definition_config() : void
    {                
        $this->assertEquals( 
            TaskDefinition::class, 
            $this->configService->getDefinitionClass('task')
        ); 
    }
    
    /**
     * Check invalid entity code request throws config exception. 
     *
     * @return void
    */
    public function test_invalid_entity_definition_config() : void
    {
        $this->expectException(ConfigurationException::class);
        $this->configService->getDefinitionClass('wizzer');
    }
}
