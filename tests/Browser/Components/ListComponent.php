<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser\Components;
 
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
 
final class ListComponent extends BaseComponent
{
    private string $listSelector;
    
    /**
     * Constructor
     * 
     * @param string $listSelector
    */
    public function __construct($listSelector)
    {
        $this->listSelector = $listSelector;    
    }
    
    /**
     * Get the root selector for the component.
     * 
     * @return string
     */
    public function selector(): string
    {
        return $this->listSelector;
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
        $browser->waitFor("{$this->listSelector} tbody tr");
    }
 
    /**
     * Get the element shortcuts for the component.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@create-entity-button' => 'header .create-entity-button',
        ];
    }
    
    /**
     * Click a header on the list component.
     * 
     * @param Browser $browser
     * @param int $offset
     *
     * @return void
    */
    public function clickHeader(Browser $browser, int $offset) : void
    {
        $selector = $this->listHeaderSelector($offset);
        $browser->waitFor($selector);
        $browser->click($selector);
    }
    
    /**
     * Wait for table cell text to be displayed.
     * 
     * @param Browser $browser
     * @param int $row
     * @param int $column
     * @param string $text
     *
     * @return void
    */
    public function waitForCellText(
        Browser $browser, 
        int $row, 
        int $column, 
        string $text
    ) : void
    {
        $browser->waitForTextIn($this->listCellSelector($row, $column), $text);
    }
    
    /**
     * Assertion for cell text.
     * 
     * @param Browser $browser
     * @param int $row
     * @param int $column
     * @param string $text
     *
     * @return void
    */
    public function assertCellText(
        Browser $browser, 
        int $row, 
        int $column, 
        string $text
    ) : void
    {
        $selector = $this->listCellSelector($row, $column);
        $browser->waitFor($selector);
        $browser->assertSeeIn($selector, $text);
    }
    
    /**
     * Assertion for header text.
     * 
     * @param Browser $browser
     * @param int $offset
     * @param string $text
     *
     * @return void
    */
    public function assertHeaderText(
        Browser $browser, 
        int $offset, 
        string $text
    ) : void
    {
        $selector = $this->listHeaderSelector($offset);
        $browser->waitFor($selector);
        $browser->assertSeeIn($selector, $text);
    }
    
    /**
     * Assert existence of elements within a cell
     * 
     * @param Browser $browser
     * @param int $row
     * @param int $column
     * @param array<string> $elementSelectors
     *
     * @return void
    */
    public function assertCellElementsVisible(
        Browser $browser, 
        int $row,
        int $column, 
        array $elementSelectors
    ) : void
    {
        $cellSelector = $this->listCellSelector($row, $column);
        $browser->waitFor($cellSelector);
        
        foreach($elementSelectors as $elementSelector) {
            $browser->assertVisible("{$cellSelector} {$elementSelector}");
        }
    }
    
    /**
     * Assert non-existence of elements within a cell
     * 
     * @param Browser $browser
     * @param int $row
     * @param int $column
     * @param array<string> $elementSelectors
     *
     * @return void
    */
    public function assertCellElementsNotPresent(
        Browser $browser, 
        int $row,
        int $column, 
        array $elementSelectors
    ) : void
    {
        $cellSelector = $this->listCellSelector($row, $column);
        $browser->waitFor($cellSelector);
        
        foreach($elementSelectors as $elementSelector) {
            $browser->assertNotPresent("{$cellSelector} {$elementSelector}");
        }
    }
    
    /**
     * Assert non-existence of table cell
     * 
     * @param Browser $browser
     * @param int $row
     * @param int $column
     *
     * @return void
    */
    public function assertCellNotPresent(
        Browser $browser, 
        int $row,
        int $column
    ) : void
    {
        $browser->assertNotPresent($this->listCellSelector($row, $column));
    }
    
    /**
     * Click button within cell
     * 
     * @param Browser $browser
     * @param int $row
     * @param int $column
     * @param string $buttonSelector
     *
     * @return void
    */
    public function clickCellButton(
        Browser $browser, 
        int $row,
        int $column, 
        string $buttonSelector
    ) : void
    {
        $cellSelector = $this->listCellSelector($row, $column);
        $browser->waitFor($cellSelector);
        $browser->click("{$cellSelector} {$buttonSelector}");
    }
    
    /**
     * Get the selector for a list body cell.
     * 
     * @param int $row
     * @param int $column
     *
     * @return string
    */
    private function listCellSelector(int $row, int $column): string
    {
        return "tbody tr:nth-of-type({$row}) td:nth-of-type({$column})";
    }
    
    /**
     * Get the selector for a list header cell.
     * 
     * @param int $offset
     *
     * @return string
    */
    private function listHeaderSelector($offset): string
    {
        return "thead th:nth-of-type({$offset})";
    }
}
