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

use  Sylius\Behat\Page\Shop\HomePageInterface as BaseHomePageInterface;

interface HomePageInterface extends BaseHomePageInterface
{
    public function isMenu(string $menuCode): bool;

    public function isMenuItem(string $menuItemTitle): bool;

    public function itemHasImage(string $itemTitle): bool;

    public function getMenuTitle(string $menuCode): string;

    public function getNumberItemsOfMenu(string $menuCode): int;

    public function getMenuItemImageSrc(string $itemTitle): ?string;

    public function getMenuItemTarget(string $itemTitle): string;

    public function getItemTitleAtPosition(int $expectedPosition): string;

    public function clickOnMenuItemLink(string $linkTitle): void;

    public function getItemLinkReference(string $itemTitle): string;
}
