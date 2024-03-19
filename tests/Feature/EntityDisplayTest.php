<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Adepta\Proton\Tests\Models\User;

class EntityDisplayTest extends TestCase
{
    /**
     * Check the entity display configuration endpoint.
     *
     * @return void
    */
    public function test_entity_display_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(1));
        
        $response = $this->get(route('proton.config.view.display', [
            'entity_code' => 'project',
            'entity_id' => 1,
        ]));
         
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('title', 'Project')
                ->has('lists', 1)
                ->has('lists.0', fn (AssertableJson $json) =>
                    $json->where('title', 'Tasks')
                        ->has('listSettings', fn (AssertableJson $json) =>
                            $json->where('entityCode', 'task')
                                ->where('contextCode', 'project')
                                ->where('contextId', 1)
                        )
                )
                ->has('displaySettings', fn (AssertableJson $json) =>
                    $json->where('entityCode', 'project')
                        ->where('entityId', 1)
                )
        );
    }
    
    /**
     * Check response with unauthorised user.
     *
     * @return void
    */
    public function test_unauthed_entity_display_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(3));
        
        $response = $this->get(route('proton.config.view.display', [
            'entity_code' => 'project',
            'entity_id' => 1,
        ]));
         
        $response->assertStatus(403);
    }
}
