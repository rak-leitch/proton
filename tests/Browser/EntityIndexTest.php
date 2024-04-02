<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser;

use Laravel\Dusk\Browser;
use Adepta\Proton\Tests\BrowserTestCase;
use Adepta\Proton\Tests\Models\User;
use Adepta\Proton\Tests\Browser\Components\ListComponent;
use Adepta\Proton\Tests\Browser\Components\MenuComponent;
use Adepta\Proton\Tests\Database\Seeders\ProjectSeeder;
 
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
                        ->assertCellText(1, 3, ProjectSeeder::getData(1, 'name'))
                        ->assertCellText(1, 4, ProjectSeeder::getData(1, 'description'))
                        ->assertCellText(2, 3, ProjectSeeder::getData(2, 'name'))
                        ->assertCellText(2, 4, ProjectSeeder::getData(2, 'description'))
                        ->assertCellElementsVisible(1, 6, ['.update-button', '.display-button', '.delete-button'])
                        ->assertCellElementsNotPresent(2, 6, ['.update-button', '.display-button', '.delete-button'])
                        ->assertCellNotPresent(3, 1);
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
                        ->assertCellText(1, 3, ProjectSeeder::getData(2, 'name'))
                        ->assertCellText(1, 4, ProjectSeeder::getData(2, 'description'));
                });
        });  
    }
    
    /**
     * Test to check entity deletion.
     *
     * @return void
    */
    public function test_project_index_delete(): void
    {        
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::findOrFail(1))
                ->visit(url('proton/entity/project/index'))
                ->within(new ListComponent('@list-project'), function (Browser $browser) {
                    $browser->clickCellButton(1, 6, '.delete-button')
                        ->clickDeleteConfirmButton()
                        ->waitForCellText(1, 1, '2')
                        ->assertCellText(1, 3, ProjectSeeder::getData(2, 'name'))
                        ->assertCellText(1, 4, ProjectSeeder::getData(2, 'description'))
                        ->assertCellNotPresent(2, 1);
                });
        });  
    }
    
    /**
     * Test to check entity index menu.
     *
     * @return void
    */
    public function test_index_menu(): void
    {        
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::findOrFail(1))
                ->visit(url('proton/entity/task/index'))
                ->within(new MenuComponent(), function (Browser $browser) {
                    $browser->openMenu()
                        ->clickMenuItem('project');
                })
                ->within(new ListComponent('@list-project'), function (Browser $browser) {
                    $browser->waitForCellText(2, 3, ProjectSeeder::getData(2, 'name'))
                        ->assertCellText(2, 4, ProjectSeeder::getData(2, 'description'));
                })
                ->assertSeeIn('.v-card-title', 'Projects');
        });  
    }
}
