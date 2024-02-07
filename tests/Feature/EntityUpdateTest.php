<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Adepta\Proton\Tests\Models\User;

class EntityUpdateTest extends TestCase
{
    /**
     * Check the entity update configuration endpoint.
     *
     * @return void
    */
    public function test_entity_update_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(1));
        
        $response = $this->get(route('proton.config.view.update', [
            'entity_code' => 'project',
            'entity_id' => 1
        ]));
         
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('entity_code', 'project')
                 ->where('entity_id', 1)
                 ->where('title', 'Update Project')
        );
    }
    
    /**
     * Check response with unauthorised user.
     *
     * @return void
    */
    public function test_unauthed_entity_update_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(2));
        
        $response = $this->get(route('proton.config.view.update', [
            'entity_code' => 'project',
            'entity_id' => 2,
        ]));
         
        $response->assertStatus(403);
    }
}
