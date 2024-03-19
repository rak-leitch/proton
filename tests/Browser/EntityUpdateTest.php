<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser;

use Laravel\Dusk\Browser;
use Adepta\Proton\Tests\BrowserTestCase;
use Adepta\Proton\Tests\Models\User;
use Adepta\Proton\Tests\Browser\Components\ListComponent;
use Adepta\Proton\Tests\Browser\Components\FormComponent;
 
class EntityUpdateTest extends BrowserTestCase
{
    const PROJECT_NAME = 'Changed project name';
    
    /**
     * Test to check the update of a project.
     *
     * @return void
    */
    public function test_project_update(): void
    {        
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::findOrFail(1))
                ->visit(url('proton/entity/project/index'))
                ->within(new ListComponent('@list-project'), function (Browser $browser) {
                    $browser->clickCellButton(1, 6, '.update-button');
                })
                ->within(new FormComponent(), function (Browser $browser) {
                    $browser
                        ->assertFieldValue('user_id', '1')
                        ->assertFieldValue('name', 'Do it yourself')
                        ->assertFieldValue('description', 'All the DIY jobs that need to be done.')
                        ->clearTextFieldValue('name')
                        ->typeInField('name', self::PROJECT_NAME)
                        ->click('@form-submit');
                })
                ->within(new ListComponent('@list-project'), function (Browser $browser) {
                    $browser->assertCellText(1, 3, self::PROJECT_NAME);
                });
        });
    }
}
