<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser;

use Laravel\Dusk\Browser;
use Adepta\Proton\Tests\BrowserTestCase;
use Illuminate\Support\Facades\Config;
 
class IndexTest extends BrowserTestCase
{
    /**
     * Test to check projects table renders
     *
     * @return void
    */
    public function test_index(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(url('proton/entity/project/index'))
                ->waitFor('.v-data-table__thead')
                ->assertSeeIn('.v-card-title', 'Projects')
                ->assertSeeIn('.v-data-table__thead', 'id')
                ->assertSeeIn('.v-data-table__thead', 'name')
                ->assertSeeIn('.v-data-table__thead', 'description')
                ->assertSeeIn('.v-data-table__thead', 'priority');
        });
    }
}
