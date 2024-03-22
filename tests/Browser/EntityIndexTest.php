<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser;

use Laravel\Dusk\Browser;
use Adepta\Proton\Tests\BrowserTestCase;
use Adepta\Proton\Tests\Models\User;
use Adepta\Proton\Tests\Browser\Components\ListComponent;
 
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
                ->waitFor('.v-card-title')
                ->assertSeeIn('.v-card-title', 'Projects')
                ->within(new ListComponent('@list-project'), function (Browser $browser) {
                    $browser->assertHeaderText(1, 'Id')
                        ->assertHeaderText(2, 'User')
                        ->assertHeaderText(3, 'Name')
                        ->assertHeaderText(4, 'Description')
                        ->assertHeaderText(5, 'Priority')
                        ->assertHeaderText(6, 'Actions')
                        ->assertCellText(1, 3, 'Do it yourself')
                        ->assertCellText(1, 4, 'All the DIY jobs that need to be done.')
                        ->assertCellText(2, 3, 'Fun')
                        ->assertCellText(2, 4, 'Non-boring things to do.')
                        ->assertCellElementsVisible(1, 6, ['.update-button', '.display-button', '.delete-button'])
                        ->assertCellElementsNotPresent(2, 6, ['.update-button', '.display-button', '.delete-button']);
                });
        });
                
    }
    
    /**
     * Test to check projects table sorts OK.
     *
     * @return void
    */
    public function test_project_index_sort(): void
    {        
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::findOrFail(1))
                ->visit(url('proton/entity/project/index'))
                ->within(new ListComponent('@list-project'), function (Browser $browser) {
                    $browser->clickHeader(1)
                        ->clickHeader(1)
                        ->waitForCellText(1, 1, '2')
                        ->assertCellText(1, 3, 'Fun')
                        ->assertCellText(1, 4, 'Non-boring things to do.');
                });
        });  
    }
}
