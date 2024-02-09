<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser;

use Laravel\Dusk\Browser;
use Adepta\Proton\Tests\BrowserTestCase;
use Adepta\Proton\Tests\Models\User;
use Adepta\Proton\Tests\Browser\Utilities\Selector;
 
class EntityIndexTest extends BrowserTestCase
{
    /**
     * Test to check projects table renders OK.
     *
     * @return void
    */
    public function test_project_index(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::findOrFail(1))
                ->visit(url('proton/entity/project/index'))
                ->waitFor('.v-data-table thead')
                //Check the card title
                ->assertSeeIn('.v-card-title', 'Projects')
                //Check the list head
                ->assertSeeIn(Selector::listHeader(1), 'id')
                ->assertSeeIn(Selector::listHeader(2), 'user_id')
                ->assertSeeIn(Selector::listHeader(3), 'name')
                ->assertSeeIn(Selector::listHeader(4), 'description')
                ->assertSeeIn(Selector::listHeader(5), 'priority')
                ->waitFor('.v-data-table tbody tr')
                //Check the list content
                ->assertSeeIn(Selector::listCell(1, 3), 'Do it yourself')
                ->assertSeeIn(Selector::listCell(1, 4), 'All the DIY jobs that need to be done.')
                //Check the first row has CRUD buttons
                ->assertPresent(Selector::listCell(1, 6).' i.v-icon')
                //Check the second row has no CRUD buttons
                ->assertNotPresent(Selector::listCell(2, 6).' i.v-icon');
                
        });
    }
}
