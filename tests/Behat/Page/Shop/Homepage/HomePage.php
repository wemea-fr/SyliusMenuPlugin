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

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Page\Shop\Homepage;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use  Sylius\Behat\Page\Shop\HomePage as BaseHomePage;
use Webmozart\Assert\Assert;

class HomePage extends BaseHomePage implements HomePageInterface
{
    public function isMenu(string $menuCode): bool
    {
        try {
            $this->getMenu($menuCode);

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function isMenuItem(string $menuItemTitle): bool
    {
        try {
            $this->getMenuItem($menuItemTitle);

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function itemHasImage(string $itemTitle): bool
    {
        try {
            $this->getMenuItemImage($itemTitle);

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function getMenuTitle(string $menuCode): string
    {
        $menuTitleElement = $this->getElement('menu_title', ['%menu_code%' => $menuCode]);

        return trim($menuTitleElement->getText());
    }

    public function getNumberItemsOfMenu(string $menuCode): int
    {
        $menu = $this->getMenu($menuCode);

        $items = $menu->findAll('css', '[data-test-wemea-sylius-menu-item]');

        return count($items);
    }

    public function getMenuItemImageSrc(string $itemTitle): ?string
    {
        $image = $this->getMenuItemImage($itemTitle);

        return $image->getAttribute('src');
    }

    public function getMenuItemTarget(string $itemTitle): string
    {
        $itemElement = $this->getMenuItem($itemTitle);

        Assert::true(
            $itemElement->hasAttribute('target'),
            'The target is not defined',
        );

        return $itemElement->getAttribute('target');
    }

    public function getItemTitleAtPosition(int $expectedPosition): string
    {
        $defaultMenuCode = 'homepage_footer_menu';
        $menu = $this->getMenu($defaultMenuCode);
        $items = $menu->findAll('css', '[data-test-wemea-sylius-menu-item]');
        Assert::true(
            count($items) >= $expectedPosition,
            sprintf('The position %d does not exit. Max position : %d', $expectedPosition, count($items)),
        );

        return $items[$expectedPosition - 1]->getAttribute('data-test-wemea-sylius-menu-item');
    }

    public function getItemLinkReference(string $itemTitle): string
    {
        $itemElement = $this->getMenuItem($itemTitle);
        Assert::true(
            $itemElement->hasAttribute('href'),
            'The link should have "href" attribute',
        );

        return $itemElement->getAttribute('href');
    }

    public function clickOnMenuItemLink(string $linkTitle): void
    {
        $itemElement = $this->getMenuItem($linkTitle);
        $itemElement->click();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'menu' => '[data-test-wemea-sylius-menu="%menu_code%"]',
            'menu_title' => '[data-test-wemea-sylius-menu="%menu_code%"] [data-test-wemea-sylius-menu-title]',
            'menu_item' => '[data-test-wemea-sylius-menu-item="%menu_item_title%"]',
            'menu_item_image' => '[data-test-wemea-sylius-menu-item="%menu_item_title%"] img',
    ]);
    }

    /**
     * @throws ElementNotFoundException
     */
    protected function getMenu(string $menuCode): NodeElement
    {
        return $this->getElement('menu', ['%menu_code%' => $menuCode]);
    }

    /**
     * @throws ElementNotFoundException
     */
    protected function getMenuItem(string $menuItemTitle): NodeElement
    {
        return $this->getElement('menu_item', ['%menu_item_title%' => $menuItemTitle]);
    }

    /**
     * @throws ElementNotFoundException
     */
    protected function getMenuItemImage(string $menuItemTitle): NodeElement
    {
        return $this->getElement('menu_item_image', ['%menu_item_title%' => $menuItemTitle]);
    }
}
