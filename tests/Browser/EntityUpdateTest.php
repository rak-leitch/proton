<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser;

use Laravel\Dusk\Browser;
use Adepta\Proton\Tests\BrowserTestCase;
use Adepta\Proton\Tests\Models\User;
use Adepta\Proton\Tests\Browser\Utilities\Selector;
 
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
            /** @phpstan-ignore-next-line  */
            $browser
                ->loginAs(User::findOrFail(1))
                //Go to the project index page and click the first update button
                ->visit(url('proton/entity/project/index'))
                ->waitFor('.v-data-table tbody tr')
                ->click('@update-1')
                //Edit in the form and submit
                ->waitFor('form.v-form')
                ->clearVue('@field-name')
                ->type('@field-name', self::PROJECT_NAME)
                ->click('@form-submit')
                //Check the updated value is in the list
                ->waitFor('.v-data-table tbody tr')
                ->assertSeeIn(Selector::listCell(1, 3), self::PROJECT_NAME);
        });
    }
}
