<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser;

use Laravel\Dusk\Browser;
use Adepta\Proton\Tests\BrowserTestCase;
use Illuminate\Support\Facades\Config;
use Adepta\Proton\Tests\Models\User;
 
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
                ->waitFor('.v-data-table__thead')
                //Check the card title
                ->assertSeeIn('.v-card-title', 'Projects')
                //Check the list head
                ->assertSeeIn('.v-data-table__thead', 'id')
                ->assertSeeIn('.v-data-table__thead', 'user_id')
                ->assertSeeIn('.v-data-table__thead', 'name')
                ->assertSeeIn('.v-data-table__thead', 'description')
                ->assertSeeIn('.v-data-table__thead', 'priority')
                ->waitFor('.v-data-table__tr')
                //Check the list content
                ->assertSeeIn('.v-data-table__tr', 'Do it yourself')
                ->assertSeeIn('.v-data-table__tr', 'All the DIY jobs that need to be done.')
                //Check the first row has CRUD buttons
                ->assertPresent('.v-data-table tbody :nth-child(1) i.v-icon')
                //Check the second row has no CRUD buttons
                ->assertNotPresent('.v-data-table tbody :nth-child(2) i.v-icon');
                
        });
    }
}
