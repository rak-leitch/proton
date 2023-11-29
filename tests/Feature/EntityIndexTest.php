<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class EntityIndexTest extends TestCase
{
    /**
     * Basic test to check the entity index route
     * for a project returns 200 and returns correct
     * config JSON.
     *
     * @return void
    */
    public function test_entity_index() : void
    {        
        $response = $this->get(route('proton.config.index', ['entity_code' => 'project']));
         
        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('list_fields', 4, fn (AssertableJson $json) =>
                $json->where('field_name', 'id')
                     ->where('sortable', true)
            )
        );
    }
}
