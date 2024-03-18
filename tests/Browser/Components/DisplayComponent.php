<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser\Components;
 
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
 
final class DisplayComponent extends BaseComponent
{
    /**
     * Get the root selector for the component.
     * 
     * @return string
     */
    public function selector(): string
    {
        return 'div.display-component';
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
        return [

        ];
    }
    
    
    /**
     * Assertion for table row values
     * 
     * @param Browser $browser
     * @param int $row
     * @param string $label
     * @param string $value
     *
     * @return void
    */
    public function assertRowValues(
        Browser $browser, 
        int $row, 
        string $label, 
        string $value
    ) : void
    {
        $browser->assertSeeIn($this->tableCellSelector($row, 1), $label);
        $browser->assertSeeIn($this->tableCellSelector($row, 2), $value);
    }
    
    /**
     * Get the selector for a table body cell.
     * 
     * @param int $row
     * @param int $column
     *
     * @return string
    */
    private function tableCellSelector(int $row, int $column): string
    {
        return "tbody tr:nth-of-type({$row}) td:nth-of-type({$column})";
    }
}
