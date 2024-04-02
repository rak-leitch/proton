<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser\Components;
 
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
 
final class MenuComponent extends BaseComponent
{    
    /**
     * Get the root selector for the component.
     * 
     * @return string
     */
    public function selector(): string
    {
        return '.index-menu-button';
    }
 
    /**
     * Assert that the browser page contains the component.
     * 
     * @param Browser $browser
     * 
     * @return void
     */
    public function assert(Browser $browser): void
    {
        $browser->waitFor($this->selector());
    }
 
    /**
     * Get the element shortcuts for the component.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [];
    }
    
    /**
     * Open the menu
     * 
     * @param Browser $browser
     *
     * @return void
    */
    public function openMenu(
        Browser $browser,
    ) : void
    {
        $browser->click('.v-btn__content');
    }
    
    /**
     * Click menu item
     * 
     * @param Browser $browser
     * @param string $entityCode
     *
     * @return void
    */
    public function clickMenuItem(
        Browser $browser,
        string $entityCode,
    ) : void
    {
        $optionsSelector = '.v-overlay-container .v-overlay .v-list';
        $browser->elsewhereWhenAvailable($optionsSelector, function (Browser $browser) use ($entityCode) {
            $browser->click(".entity-index-{$entityCode}");
        });
    }
}
