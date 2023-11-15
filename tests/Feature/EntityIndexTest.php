<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;

class EntityIndexTest extends TestCase
{
    /**
     * Basic test to check the ebtity index route
     * for a project returns 200.
     *
     * @return void
    */
    public function test_entity_index() : void
    {        
        $response = $this->get(route('proton.config.index', ['entity_code' => 'project']));
        $response->assertStatus(200);
    }
}
