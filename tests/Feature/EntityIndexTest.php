<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Adepta\Proton\Tests\Models\User;

class EntityIndexTest extends TestCase
{
    /**
     * Check the entity index configuration endpoint.
     *
     * @return void
    */
    public function test_entity_index_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(1));
        
        $response = $this->get(route('proton.config.view.index', [
            'entity_code' => 'project',
        ]));
         
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('entity_code', 'project')
                 ->where('entity_label_plural', 'Projects')
        );
    }
    
    /**
     * Check response with unauthorised user.
     *
     * @return void
    */
    public function test_unauthed_entity_index_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(2));
        
        $response = $this->get(route('proton.config.view.index', [
            'entity_code' => 'project',
        ]));
         
        $response->assertStatus(403);
    }
}
