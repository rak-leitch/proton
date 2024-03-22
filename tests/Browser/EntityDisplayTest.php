<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser;

use Laravel\Dusk\Browser;
use Adepta\Proton\Tests\BrowserTestCase;
use Adepta\Proton\Tests\Models\User;
use Adepta\Proton\Tests\Browser\Components\ListComponent;
use Adepta\Proton\Tests\Browser\Components\DisplayComponent;
 
class EntityDisplayTest extends BrowserTestCase
{
    /**
     * Test to check the update of a project.
     *
     * @return void
    */
    public function test_project_display(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::findOrFail(1);
            $browser
                ->loginAs($user)
                ->visit(url('proton/entity/project/index'))
                ->within(new ListComponent('@list-project'), function (Browser $browser) {
                    $browser->clickCellButton(1, 6, '.display-button');
                })
                ->within(new DisplayComponent(), function (Browser $browser) use ($user) {
                    $browser
                        ->assertRowValues(1, 'Id', '1')
                        ->assertRowValues(2, 'User', $user->name)
                        ->assertRowValues(3, 'Name', 'Do it yourself')
                        ->assertRowValues(4, 'Project Description', 'All the DIY jobs that need to be done.')
                        ->assertRowValues(5, 'Priority', 'normal');
                })
                ->within(new ListComponent('@list-task'), function (Browser $browser) {
                    $browser->assertHeaderText(1, 'Id')
                        ->assertHeaderText(2, 'Project')
                        ->assertHeaderText(3, 'Name')
                        ->assertHeaderText(4, 'Description')
                        ->assertHeaderText(5, 'Actions')
                        ->assertCellText(1, 1, '1')
                        ->assertCellText(1, 2, 'Do it yourself')
                        ->assertCellText(1, 3, 'Paint the bedroom.')
                        ->assertCellText(1, 4, 'Needs to be pink.')
                        ->assertCellText(2, 1, '2')
                        ->assertCellText(2, 2, 'Do it yourself')
                        ->assertCellText(2, 3, 'Repair the sink drain.')
                        ->assertCellText(2, 4, 'It is leaking everywhere.')
                        ->assertCellNotPresent(3, 1);
                });
        
        });
    }
}
