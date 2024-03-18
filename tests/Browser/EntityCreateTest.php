<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser;

use Laravel\Dusk\Browser;
use Adepta\Proton\Tests\BrowserTestCase;
use Adepta\Proton\Tests\Models\User;
use Adepta\Proton\Tests\Browser\Components\ListComponent;
use Adepta\Proton\Tests\Browser\Components\FormComponent;
 
class EntityCreateTest extends BrowserTestCase
{
    const PROJECT_NAME = 'Browser testing project';
    const PROJECT_DESC = 'A description of the browser testing project';
    
    /**
     * Test to check creation of a project.
     *
     * @return void
    */
    public function test_project_create(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::findOrFail(1))
                ->visit(url('proton/entity/project/index'))
                ->within(new ListComponent('@list-project'), function (Browser $browser) {
                    $browser->click('@create-entity-button');
                })
                ->within(new FormComponent(), function (Browser $browser) {
                    $browser->click('@form-submit')
                        ->assertFieldError('user_id', 'The user id field is required.')
                        ->assertFieldError('name', 'The name field is required.')
                        ->assertFieldError('priority', 'The priority field is required.')
                        ->changeSelectField('user_id', 1)
                        ->typeInField('name', self::PROJECT_NAME)
                        ->typeInField('description', self::PROJECT_DESC)
                        ->typeInField('priority', 'urgent')
                        ->click('@form-submit');
                })
                ->within(new ListComponent('@list-project'), function (Browser $browser) {
                    $browser->assertCellText(3, 3, self::PROJECT_NAME)
                        ->assertCellText(3, 4, self::PROJECT_DESC);
                });
        });
    }
}
