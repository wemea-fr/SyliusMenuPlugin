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

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Tests\Wemea\SyliusMenuPlugin\Behat\Helper\ImageMatcherHelperInterface;
use Tests\Wemea\SyliusMenuPlugin\Behat\Page\Shop\Homepage\HomePageInterface;
use Webmozart\Assert\Assert;

class HomepageContext implements Context
{
    /** @var HomePageInterface */
    protected $homepage;

    /** @var ImageMatcherHelperInterface */
    protected $imageMatcher;

    public function __construct(HomePageInterface $homepage, ImageMatcherHelperInterface $imageMatcher)
    {
        $this->homepage = $homepage;
        $this->imageMatcher = $imageMatcher;
    }

    /**
     * @Given I go to the homepage
     * @Given I am on the homepage
     */
    public function iGoToTheHomepage()
    {
        $this->homepage->open();
    }

    /**
     * @Then /^I should( not)? see the (\w+) menu$/
     */
    public function iShouldSeeTheMenu(bool $not, string $menuCode)
    {
        if ($not) {
            Assert::false(
                $this->homepage->isMenu($menuCode),
                'The menu is visible. Expected not.',
            );
        } else {
            Assert::true(
                $this->homepage->isMenu($menuCode),
                'The menu is not displayed.',
            );
        }
    }

    /**
     * @Given the title of the :menuCode menu is :expectedTitle
     */
    public function theTitleOfTheMenuIs(string $menuCode, string $expectedTitle)
    {
        $currentTitle = $this->homepage->getMenuTitle($menuCode);

        Assert::same(
            $currentTitle,
            $expectedTitle,
        );
    }

    /**
     * @Then /^the menu (\w+) has (0|1) item$/
     * @Then /^the menu (\w+) has (\d+) items$/
     */
    public function theMenuHasItems(string $menuCode, int $expectedItems)
    {
        $numberItems = $this->homepage->getNumberItemsOfMenu($menuCode);
        Assert::eq(
            $numberItems,
            $expectedItems,
            sprintf('There is %d item%s. Expected %d', $numberItems, $numberItems > 1 ? 's' : '', $expectedItems),
        );
    }

    /**
     * @Given /^I should see "([^"]*)" item$/
     */
    public function iShouldSeeItem(string $menuItemTitle)
    {
        Assert::true(
            $this->homepage->isMenuItem($menuItemTitle),
            sprintf('Item with title "%s" not found', $menuItemTitle),
        );
    }

    /**
     * @Then /^I should see "([^"]*)" item with "([^"]*)" icon$/
     */
    public function iShouldSeeItemWithIcon(string $itemTitle, string $imageName)
    {
        Assert::true(
            $this->homepage->itemHasImage($itemTitle),
            sprintf('Item with title "%s" has not icon', $itemTitle),
        );

        $imgSrc = $this->homepage->getMenuItemImageSrc($itemTitle);
        $this->imageMatcher->liipImagineGeneratedImageMatchToSyliusFixture($imageName, $imgSrc);
    }

    /**
     * @Then /^the "([^"]*)" item should open link in (new|same) tab$/
     */
    public function theItemShouldOpenLinkInNewTab(string $itemTitle, string $expectedTab)
    {
        if ($expectedTab === 'new') {
            Assert::same(
                $this->homepage->getMenuItemTarget($itemTitle),
                '_blank',
                'This item should have "_blank" as target value',
            );
        } else {
            Assert::same(
                $this->homepage->getMenuItemTarget($itemTitle),
                '_self',
                'This item should have "self" as target value',
            );
        }
    }

    /**
     * @Then /^I should see "([^"]*)" item at the (\d)(?:st|nd|rd|th) position$/
     */
    public function iShouldSeeAtThePosition(string $itemTitle, int $expectedPosition)
    {
        $currentTitle = $this->homepage->getItemTitleAtPosition($expectedPosition);
        Assert::same(
            $currentTitle,
            $itemTitle,
            sprintf('Is "%s" at the position %d. Expected to have "%s"', $currentTitle, $expectedPosition, $itemTitle),
        );
    }

    /**
     * @Given /^I follow the "([^"]*)" link$/
     */
    public function iFollowTheLink(string $linkTitle)
    {
        $this->homepage->clickOnMenuItemLink($linkTitle);
    }

    /**
     * @Given /^"([^"]*)" item should redirect on "([^"]*)"$/
     */
    public function itemShouldRedirectOn(string $itemTitle, string $expectedReference)
    {
        $currentReference = $this->homepage->getItemLinkReference($itemTitle);

        Assert::same(
            $currentReference,
            $expectedReference,
            sprintf('the link redirect on "%s". Should redirect on : "%s', $currentReference, $expectedReference),
        );
    }
}
