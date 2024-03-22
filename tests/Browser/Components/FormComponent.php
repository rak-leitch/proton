<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser\Components;
 
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
use Exception;
 
final class FormComponent extends BaseComponent
{
    /**
     * Get the root selector for the component.
     * 
     * @return string
     */
    public function selector(): string
    {
        return 'form.v-form';
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
            '@form-submit' => 'button.form-submit',
        ];
    }
    
    /**
     * After a form submit, assert a field error text
     * is displayed.
     * 
     * @param Browser $browser
     * @param string $field
     * @param string $errorText
     *
     * @return void
     */
    public function assertFieldError(Browser $browser, string $field, string $errorText)
    {
        $errorSelector = ".field-{$field} .v-input__details .v-messages .v-messages__message";
        $browser->waitFor($errorSelector)
            ->assertSeeIn($errorSelector, $errorText);
    }
    
    /**
     * Change the value of a select field
     * 
     * @param Browser $browser
     * @param string $field
     * @param int $valueOffset
     *
     * @return void
     */
    public function changeSelectField(Browser $browser, string $field, int $valueOffset)
    {
        $optionsSelector = '.v-overlay-container .v-overlay .v-select__content';
        $browser->click(".field-{$field} .v-select__menu-icon");
        $browser->elsewhereWhenAvailable($optionsSelector, function (Browser $browser) use ($valueOffset) {
            $browser->click(".v-list:nth-child({$valueOffset})");
        });
    }
    
    /**
     * Type in a field
     * 
     * @param Browser $browser
     * @param string $field
     * @param string $value
     *
     * @return void
     */
    public function typeInField(Browser $browser, string $field, string $value)
    {
        $fieldInputSelector = ".field-{$field} input";
        $this->typeInElement($browser, $fieldInputSelector, $field, $value);
    }
    
    /**
     * Type in a textarea
     * 
     * @param Browser $browser
     * @param string $field
     * @param string $value
     *
     * @return void
     */
    public function typeInArea(Browser $browser, string $field, string $value)
    {
        $fieldInputSelector = ".field-{$field} textarea";
        $this->typeInElement($browser, $fieldInputSelector, $field, $value);
    }
    
    /**
     * Type in selected element 
     * 
     * @param Browser $browser
     * @param string $fieldInputSelector
     * @param string $field
     * @param string $value
     *
     * @return void
     */
    private function typeInElement(
        Browser $browser, 
        string $fieldInputSelector, 
        string $field, 
        string $value
    ) {
        $browser->assertPresent($fieldInputSelector);
        $element = $browser->element($fieldInputSelector);
        if($element) {
            $element->sendKeys($value);
        } else {
            throw new Exception('Failed finding field to fill');
        }
    }
    
    /**
     * Assert field value
     * 
     * @param Browser $browser
     * @param string $field
     * @param string $value
     *
     * @return void
     */
    public function assertFieldValue(Browser $browser, string $field, string $value)
    {
        $fieldInputSelector = ".field-{$field} input";
        $browser->assertValue($fieldInputSelector, $value);
    }
    
    /**
     * Assert area value
     * 
     * @param Browser $browser
     * @param string $field
     * @param string $value
     *
     * @return void
     */
    public function assertAreaValue(Browser $browser, string $field, string $value)
    {
        $fieldInputSelector = ".field-{$field} textarea";
        $browser->assertValue($fieldInputSelector, $value);
    }
    
    /**
     * Clear a text field's value
     * 
     * @param Browser $browser
     * @param string $field
     *
     * @return void
     */
    public function clearTextFieldValue(Browser $browser, string $field)
    {
        $fieldInputSelector = ".field-{$field} input";
        $browser->clearVue($fieldInputSelector);
    }
}
