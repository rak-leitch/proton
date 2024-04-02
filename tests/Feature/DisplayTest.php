<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Adepta\Proton\Tests\Models\User;
use Adepta\Proton\Tests\Database\Seeders\ProjectSeeder;

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
                    $json->where('title', 'Id')
                        ->where('key', 'id')
                        ->where('frontendType', 'text')
                        ->where('value', 1)
                )
                ->has('fields.1', fn (AssertableJson $json) =>
                    $json->where('title', 'User')
                        ->where('key', 'user_id')
                        ->where('frontendType', 'text')
                        ->where('value', $user->name)
                )
                ->has('fields.2', fn (AssertableJson $json) =>
                    $json->where('title', 'Name')
                        ->where('key', 'name')
                        ->where('frontendType', 'text')
                        ->where('value', ProjectSeeder::getData(1, 'name'))
                )
                ->has('fields.3', fn (AssertableJson $json) =>
                    $json->where('title', 'Project Description')
                        ->where('key', 'description')
                        ->where('frontendType', 'text')
                        ->where('value', ProjectSeeder::getData(1, 'description'))
                )
                ->has('fields.4', fn (AssertableJson $json) =>
                    $json->where('title', 'Priority')
                        ->where('key', 'priority')
                        ->where('frontendType', 'text')
                        ->where('value', ProjectSeeder::getData(1, 'priority'))
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
