<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;

class IndexTest extends TestCase
{
    /**
     * Basic test to check the main route exists
     *
     * @return void
    */
    public function test_index_page() : void
    {        
        $response = $this->get(route('proton.index'));
        $response->assertStatus(500);
    }
}
