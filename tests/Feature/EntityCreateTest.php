<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Adepta\Proton\Tests\Models\User;

class EntityCreateTest extends TestCase
{
    /**
     * Check the entity create configuration endpoint.
     *
     * @return void
    */
    public function test_entity_create_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(1));
        
        $response = $this->get(route('proton.config.view.create', [
            'entity_code' => 'project',
        ]));
         
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('entityCode', 'project')
                 ->where('title', 'New Project')
        );
    }
    
    /**
     * Check response with unauthorised user.
     *
     * @return void
    */
    public function test_unauthed_entity_create_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(3));
        
        $response = $this->get(route('proton.config.view.create', [
            'entity_code' => 'project',
        ]));
         
        $response->assertStatus(403);
    }
}
