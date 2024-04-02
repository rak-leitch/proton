<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Adepta\Proton\Tests\Models\User;

class MenuTest extends TestCase
{
    /**
     * Check the menu configuration endpoint.
     *
     * @return void
    */
    public function test_display_config_endpoint() : void
    {        
        $user = User::findOrFail(1);
        $this->actingAs($user);
        
        $response = $this->get(route('proton.config.menu'));
         
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('entities', 2)
                ->has('entities.0', fn (AssertableJson $json) =>
                    $json->where('entityCode', 'project')
                        ->where('label', 'Projects')
                )
                ->has('entities.1', fn (AssertableJson $json) =>
                    $json->where('entityCode', 'task')
                        ->where('label', 'Tasks')
                )
        );
    }
}
