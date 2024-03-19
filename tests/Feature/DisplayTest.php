<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Adepta\Proton\Tests\Models\User;

class DisplayTest extends TestCase
{
    /**
     * Check the display configuration endpoint.
     *
     * @return void
    */
    public function test_display_config_endpoint() : void
    {        
        $user = User::findOrFail(1);
        $this->actingAs($user);
        
        $response = $this->get(route('proton.config.display', [
            'entity_code' => 'project',
            'entity_id' => 1
        ]));
         
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('fields', 5)
                ->has('fields.0', fn (AssertableJson $json) =>
                    $json->where('title', 'id')
                        ->where('key', 'id')
                        ->where('frontend_type', 'text')
                        ->where('value', 1)
                )
                ->has('fields.1', fn (AssertableJson $json) =>
                    $json->where('title', 'user_id')
                        ->where('key', 'user_id')
                        ->where('frontend_type', 'text')
                        ->where('value', $user->name)
                )
                ->has('fields.2', fn (AssertableJson $json) =>
                    $json->where('title', 'name')
                        ->where('key', 'name')
                        ->where('frontend_type', 'text')
                        ->where('value', 'Do it yourself')
                )
                ->has('fields.3', fn (AssertableJson $json) =>
                    $json->where('title', 'description')
                        ->where('key', 'description')
                        ->where('frontend_type', 'text')
                        ->where('value', 'All the DIY jobs that need to be done.')
                )
                ->has('fields.4', fn (AssertableJson $json) =>
                    $json->where('title', 'priority')
                        ->where('key', 'priority')
                        ->where('frontend_type', 'text')
                        ->where('value', 'normal')
                )
        );
    }
    
    /**
     * Check the display configuration endpoint with a 
     * user that does not have permission.
     *
     * @return void
    */
    public function test_unauthed_list_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(3));
        
        $response = $this->get(route('proton.config.display', [
            'entity_code' => 'project',
            'entity_id' => 1
        ]));
         
        $response->assertStatus(403);
    }
}
