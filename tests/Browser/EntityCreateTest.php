<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser;

use Laravel\Dusk\Browser;
use Adepta\Proton\Tests\BrowserTestCase;
use Adepta\Proton\Tests\Models\User;
use Adepta\Proton\Tests\Browser\Utilities\Selector;
 
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
                //Go to project index page and click 'New Project'
                ->visit(url('proton/entity/project/index'))
                ->waitFor('@create-entity-button')
                ->click('@create-entity-button')
                //Wait for form to load and fill it in
                ->waitFor('form.v-form')
                ->type('@field-user_id', '1')
                //Submit before required fields filled in
                ->click('@form-submit')
                ->waitFor('form.v-form .v-messages__message')
                ->assertSeeIn('form.v-form .v-messages__message', 'The name field is required.')
                ->type('@field-name', self::PROJECT_NAME)
                ->type('@field-description', self::PROJECT_DESC)
                ->type('@field-priority', 'urgent')
                ->click('@form-submit')
                //Check the new Project appears in the list
                ->waitFor('.v-data-table tbody tr')
                ->assertSeeIn(Selector::listCell(3, 3), self::PROJECT_NAME)
                ->assertSeeIn(Selector::listCell(3, 4), self::PROJECT_DESC);
                
        });
    }
}
