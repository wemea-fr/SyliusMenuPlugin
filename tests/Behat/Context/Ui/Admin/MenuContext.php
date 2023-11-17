<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Component\Locale\Converter\LocaleConverterInterface;
use Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\Menu\IndexPageInterface;
use Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\Menu\UpdatePageInterface;
use Webmozart\Assert\Assert;
use Wemea\SyliusMenuPlugin\Entity\MenuInterface;

class MenuContext implements Context
{
    /** @var IndexPageInterface */
    protected $indexPage;

    /** @var UpdatePageInterface */
    protected $updatePage;

    /** @var LocaleConverterInterface */
    protected $localeConverter;

    public function __construct(
        IndexPageInterface $indexPage,
        UpdatePageInterface $updatePage,
        LocaleConverterInterface $localeConverter,
    ) {
        $this->indexPage = $indexPage;
        $this->updatePage = $updatePage;
        $this->localeConverter = $localeConverter;
    }

    /**
     * @Given /^I try to access at admin menu page$/
     */
    public function iTryToAccessAtAdminMenuPage()
    {
        $this->indexPage->tryToOpen();
    }

    /**
     * @Then /^I should be on admin menu page$/
     */
    public function iShouldBeOnAdminMenuPage()
    {
        $this->indexPage->verify();
    }

    /**
     * @Given /^I am on admin menu page$/
     * @Given /^I want to manage menus$/
     */
    public function iAmOnAdminMenuPage()
    {
        $this->indexPage->open();
    }

    /**
     * @Given /^I try to edit (this menu)$/
     */
    public function iTryToEditThisMenu(MenuInterface $menu)
    {
        $this->updatePage->tryToOpen(['id' => $menu->getId()]);
    }

    /**
     * @Then /^I should be on edit page of (this menu)$/
     */
    public function iShouldBeOnEditPageOfThisMenu(MenuInterface $menu)
    {
        $this->updatePage->verify(['id' => $menu->getId()]);
    }

    /**
     * @Given /^I want to edit (this menu)$/
     */
    public function iWantToEditThisMenu(MenuInterface $menu)
    {
        $this->updatePage->open(['id' => $menu->getId()]);
    }

    /**
     * @Then /^I should see (1) menu$/
     * @Then /^I should see (\d+) menus$/
     */
    public function iShouldSeeMenu(int $expectedNumerMenu)
    {
        $numberMenu = $this->indexPage->countItems();
        Assert::eq(
            $numberMenu,
            $expectedNumerMenu,
            sprintf('There is %d menu%s. Expected : %d ', $numberMenu, $numberMenu > 1 ? 's' : '', $expectedNumerMenu),
        );
    }

    /**
     * @Given /^I should see "([^"]*)" column$/
     * @Given /^I should see "([^"]*)" and "([^"]*)" columns$/
     * @Given /^I should see "([^"]*)", "([^"]*)" and "([^"]*)" columns$/
     */
    public function iShouldSeeAndColumns(...$expectedColumnsName)
    {
        $columnsNames = $this->indexPage->getColumnsNames();

        foreach ($expectedColumnsName as $expectedColumn) {
            Assert::inArray(
                $expectedColumn,
                $columnsNames,
                sprintf('the column "%s not found', $expectedColumn),
            );
        }
    }

    /**
     * @Then /^I should see "([^"]*)" button with "([^"]*)" option$/
     * @Then /^I should see "([^"]*)" button with "([^"]*)" and "([^"]*)" options$/
     */
    public function iShouldSeeManageItemButton(string $buttonName, ...$dropdownOptions)
    {
        Assert::true(
            $this->indexPage->isButtonAction($buttonName),
            sprintf('The button "%s" not found', $buttonName),
        );

        foreach ($dropdownOptions as $dropdownOption) {
            Assert::true(
                $this->indexPage->ButtonHasOption($buttonName, $dropdownOption),
                sprintf('The button "%s" has not the "%s" option', $buttonName, $dropdownOption),
            );
        }
    }

    /**
     * @Then /^I should see "([^"]*)" button without "([^"]*)" option$/
     * @Then /^I should see "([^"]*)" button without ([^"]*)" and "([^"]*)" options$/
     */
    public function iShouldSeeButtonWithoutOption(string $buttonName, ...$dropdownOptions)
    {
        Assert::true(
            $this->indexPage->isButtonAction($buttonName),
            sprintf('The button "%s" not found', $buttonName),
        );

        foreach ($dropdownOptions as $dropdownOption) {
            Assert::false(
                $this->indexPage->ButtonHasOption($buttonName, $dropdownOption),
                sprintf('The button "%s" has the "%s" option. Expected not', $buttonName, $dropdownOption),
            );
        }
    }

    /**
     * @When /^I click on "([^"]*)" option for (this menu)$/
     * @When /^I click on "([^"]*)" button of (this menu)$/
     */
    public function iClickOnOptionForThisMenu(string $option, MenuInterface $menu)
    {
        $this->indexPage->clickOnActionButton($option, $menu->getCode());
    }

    /**
     * @Then /^I should see "([^"]*)" button$/
     */
    public function iShouldSeeButton(string $buttonName)
    {
        Assert::true(
            $this->indexPage->isSimpleButton($buttonName),
            sprintf('The button "%s" not found', $buttonName),
        );
    }

    /**
     * @Then /^I should see the "([^"]*)" field$/
     * @Then /^I should see the "([^"]*)" and the "([^"]*)" fields$/
     * @Then /^I should see the "([^"]*)", the "([^"]*)" and the "([^"]*)" fields$/
     */
    public function iShouldCodeField(...$expectedFields)
    {
        foreach ($expectedFields as $expectedField) {
            Assert::true(
                $this->updatePage->isField($expectedField),
                sprintf('The field "%s" not found', $expectedField),
            );
        }
    }

    /**
     * @Given /^I should see "([^"]*)" field in "([^"]*)"$/
     */
    public function iShouldSeeFieldIn(string $field, string $locale)
    {
        $localeCode = $this->localeConverter->convertNameToCode($locale);
        Assert::true(
            $this->updatePage->isTranslatableFieldInLocale($field, $localeCode),
            sprintf('The field "%s" not found on %s', $field, $locale),
        );
    }

    /**
     * @Given the :field field is disabled
     */
    public function theFieldIsDisabled(string $field)
    {
        Assert::true(
            $this->updatePage->theFieldIsDisabled($field),
            sprintf('the field "%s" is not disabled', $field),
        );
    }

    /**
     * @When /^I set empty value for "([^"]*)" in "([^"]*)"$/
     */
    public function iSetEmptyValueForIn(string $field, string $locale)
    {
        $localeCode = $this->localeConverter->convertNameToCode($locale);
        $this->updatePage->setValueOfTranslatableField('', $field, $localeCode);
    }

    /**
     * @Given I save my changes
     */
    public function saveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @Then /^I should be notified "([^"]*)" is required on "([^"]*)" with message "([^"]*)"$/
     */
    public function iShouldBeNotifiedIsRequiredOnWithMessage(string $field, string $locale, string $expectedMessage)
    {
        $localeCode = $this->localeConverter->convertNameToCode($locale);
        $errorMessage = $this->updatePage->getErrorMessageOfTranslatableField($field, $localeCode);
        Assert::same(
            $errorMessage,
            $expectedMessage,
        );
    }

    /**
     * @Given the value of :field field is :value
     */
    public function theValueOfFieldIs(string $field, string $value)
    {
        $currentValue = $this->updatePage->getFieldValue($field);
    }

    /**
     * @Given /^the menu is (enabled|disabled)$/
     */
    public function theMenuIs(string $state)
    {
        if ($state === 'enabled') {
            $this->theValueOfFieldIs('enabled', 'true');
        } else {
            $this->theValueOfFieldIs('enabled', 'false');
        }
    }

    /**
     * @When I select :value as visibility
     */
    public function iSetFor(string $value)
    {
        $this->updatePage->selectVisibilityOption($value);
    }

    /**
     * @Given /^I (disabled|enabled) this menu$/
     */
    public function iDisabledThisMenu(string $state)
    {
        if ($state === 'enabled') {
            $this->updatePage->checkEnabled(true);
        } else {
            $this->updatePage->checkEnabled(false);
        }
    }

    /**
     * @Given /^I set "([^"]*)" as "([^"]*)" in "([^"]*)"$/
     */
    public function iSetForIn(string $value, string $field, $locale)
    {
        $localeCode = $this->localeConverter->convertNameToCode($locale);
        $this->updatePage->setValueOfTranslatableField($value, $field, $localeCode);
    }

    /**
     * @Given /^the "([^"]*)" is "([^"]*)" in "([^"]*)"$/
     */
    public function theIsIn(string $field, string $value, string $locale)
    {
        $localeCode = $this->localeConverter->convertNameToCode($locale);
        $currentValue = $this->updatePage->getTranslatableFieldValue($field, $localeCode);
        Assert::same(
            $currentValue,
            $value,
        );
    }

    /**
     * @Then I should see :filter filter
     */
    public function iShouldSeeSearchFilter(string $filter)
    {
        Assert::true(
            $this->indexPage->isFilter($filter),
            'The filter not found',
        );
    }
}
