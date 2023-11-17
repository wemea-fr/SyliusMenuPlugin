<?php

declare(strict_types=1);

/*
 * This file is part of Wemea Menu plugin for Sylius.
 *
 * (c) Wemea (wemea.fr)
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\MenuItem;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;
use Sylius\Behat\Service\AutocompleteHelper;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;

class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    use ManageImageTrait;

    public function isField(string $field): bool
    {
        try {
            $this->getSimpleField($field);

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function isTranslatableField(string $field, string $localeCode): bool
    {
        try {
            $this->getTranslatableField($field, $localeCode);

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function isLinkField(string $fieldCode): bool
    {
        try {
            $this->getLinkField($fieldCode);

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function isCustomLinkFieldIn(string $localeCode): bool
    {
        try {
            $this->getElement('menu_custom_link_translation_field', ['%locale_code%' => $localeCode]);

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function linkFieldIsVisible(string $field): bool
    {
        $linkField = $this->getElement('link_input_section', ['%type%' => $field]);

        return $linkField->isVisible();
    }

    public function setFieldValue(string $field, string $value): void
    {
        $fieldElement = $this->getSimpleField($field);

        if ($fieldElement->getTagName() === 'select') {
            $fieldElement->selectOption($value);
        } else {
            $fieldElement->setValue($value);
        }
    }

    public function setTranslatableFieldValue(string $field, string $value, string $localeCode): void
    {
        $fieldElement = $this->getTranslatableField($field, $localeCode);
        $fieldElement->setValue($value);
    }

    public function setLinkFieldValue(string $field, string $value): void
    {
        $fieldElement = $this->getLinkField($field);

        if ($fieldElement->getTagName() === 'select') {
            $fieldElement->selectOption($value);
        } else {
            $fieldElement->setValue($value);
        }
    }

    public function setCustomLinkUrl(string $value, string $localeCode): void
    {
        $field = $this->getElement('menu_custom_link_translation_field', ['%locale_code%' => $localeCode]);
        $field->setValue($value);
    }

    public function setProductLink(ProductInterface $product): void
    {
        $productLinkElement = $this->getElement('product_link')->getParent();
        AutocompleteHelper::chooseValue($this->getSession(), $productLinkElement, $product->getName());
    }

    public function setTaxonLink(TaxonInterface $taxon): void
    {
        $taxonLinkElement = $this->getElement('taxon_link')->getParent();
        AutocompleteHelper::chooseValue($this->getSession(), $taxonLinkElement, $taxon->getName());
    }

    public function getLinkFieldValue(string $field): string
    {
        return $this->getLinkField($field)->getValue();
    }

    public function getCustomLinkFieldValue(string $localeCode): string
    {
        $field = $this->getElement('menu_custom_link_translation_field', ['%locale_code%' => $localeCode]);

        return $field->getValue();
    }

    public function getErrorMessageOfTranslatableField(string $field, string $localeCode): string
    {
        $fieldElement = $this->getTranslatableField($field, $localeCode);
        $errorElement = $fieldElement->getParent()->find('css', '.sylius-validation-error');

        Assert::notNull(
            $errorElement,
            sprintf('No error found for %s in %s', $field, $localeCode),
        );

        return trim($errorElement->getText());
    }

    public function getErrorMessageOfCustomLinkField(string $locale): string
    {
        $field = $this->getElement('menu_custom_link_translation_field', ['%locale_code%' => $locale]);
        $errorElement = $field->getParent()->find('css', '.sylius-validation-error');

        Assert::notNull(
            $errorElement,
            sprintf('No error found for custom link in %s', $locale),
        );

        return trim($errorElement->getText());
    }

    public function getTargetResourceLinkErrorMessage(): string
    {
        $menuLinkResourceFormContainer = $this->getElement('menu_link_resource_form');
        $errorElement = $menuLinkResourceFormContainer->getParent()->find('css', '.sylius-validation-error');

        Assert::notNull(
            $errorElement,
            'No error found for menu link',
        );

        return trim($errorElement->getText());
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            $this->getImageDefinedElements(),
            [
                'simple_field' => '#wemea_sylius_menu_menu_item_%field%',
                'translatable_field' => '#wemea_sylius_menu_menu_item_translations_%locale%_%field%',
                'link_field' => '#wemea_sylius_menu_menu_item_link_%field%',
                'product_link' => '#wemea_sylius_menu_menu_item_link_product',
                'taxon_link' => '#wemea_sylius_menu_menu_item_link_taxon',
                'link_input_section' => '[data-link-type="%type%"]',
                'menu_link_resource_form' => '[data-test-menu-link-container]',
                'menu_custom_link_translation_field' => '#wemea_sylius_menu_menu_item_link_translations_%locale_code%_customLink',
        ],
        );
    }

    /**
     * @throws ElementNotFoundException
     */
    protected function getSimpleField(string $field): NodeElement
    {
        return $this->getElement('simple_field', ['%field%' => $field]);
    }

    /**
     * @throws ElementNotFoundException
     */
    protected function getLinkField(string $field): NodeElement
    {
        return $this->getElement('link_field', ['%field%' => $field]);
    }

    /**
     * @throws ElementNotFoundException
     */
    protected function getTranslatableField(string $field, string $localeCode)
    {
        return $this->getElement('translatable_field', ['%field%' => $field, '%locale%' => $localeCode]);
    }
}
