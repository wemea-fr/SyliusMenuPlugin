<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\Menu;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;
use Webmozart\Assert\Assert;

class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    public function isField(string $expectedField): bool
    {
        try {
            $this->getFieldElement($expectedField);

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function isTranslatableFieldInLocale(string $field, string $localeCode): bool
    {
        try {
            $this->getTranslatableFieldElement($field, $localeCode);

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function theFieldIsDisabled(string $field): bool
    {
        $fieldElement = $this->getFieldElement($field);

        return $fieldElement->hasAttribute('disabled');
    }

    public function selectVisibilityOption(string $value): void
    {
        $selectElement = $this->getFieldElement('visibility');
        $selectElement->selectOption($value);
    }

    public function checkEnabled(bool $check)
    {
        $enabledInput = $this->getFieldElement('enabled');
        if ($check) {
            $enabledInput->check();
        } else {
            $enabledInput->uncheck();
        }
    }

    public function setValueOfTranslatableField(string $value, string $field, string $localeCode): void
    {
        $fieldElement = $this->getTranslatableFieldElement($field, $localeCode);
        $fieldElement->setValue($value);
    }

    public function getFieldValue(string $field): string
    {
        $fieldElement = $this->getFieldElement($field);

        if ($fieldElement->getTagName() === 'select') {
            $value = $fieldElement->getValue();

            return $fieldElement->find('css', 'option[value="' . $value . '"]')->getText();
        }

        if ($fieldElement->getAttribute('type') === 'checkbox') {
            return $fieldElement->isChecked() ? 'true' : 'false';
        }

        return $fieldElement->getValue();
    }

    public function getTranslatableFieldValue(string $field, string $localeCode): string
    {
        $fieldElement = $this->getTranslatableFieldElement($field, $localeCode);

        return $fieldElement->getValue();
    }

    public function getErrorMessageOfTranslatableField(string $field, string $localeCode): string
    {
        $fieldElement = $this->getTranslatableFieldElement($field, $localeCode);
        $errorElement = $fieldElement->getParent()->find('css', '.sylius-validation-error');
        Assert::notNull(
            $errorElement,
            sprintf('No errors message found for "%s" field', $field),
        );

        return $errorElement->getText();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'field' => '#wemea_sylius_menu_menu_%field%',
            'translatable_field' => '#wemea_sylius_menu_menu_translations_%locale%_%field%',
        ]);
    }

    /**
     * @throws ElementNotFoundException
     */
    protected function getFieldElement(string $field): NodeElement
    {
        return $this->getElement('field', ['%field%' => $field]);
    }

    /**
     * @throws ElementNotFoundException
     */
    protected function getTranslatableFieldElement(string $field, string $localeCode): NodeElement
    {
        return $this->getElement('translatable_field', ['%field%' => $field, '%locale%' => $localeCode]);
    }
}
