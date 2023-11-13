<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser;

use Laravel\Dusk\Browser;
use Adepta\Proton\Tests\BrowserTestCase;
use Illuminate\Support\Facades\Config;
 
class IndexTest extends BrowserTestCase
{
    /**
     * Basic test to visit the main route and
     * see if Vue and Vuetify are working
     *
     * @return void
    */
    public function test_index(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('proton.index'))
                ->assertSee('Proton')
                ->waitFor('@test-button')
                ->assertSeeIn('@test-button span.v-btn__content', 'WORKING');
        });
    }
}
