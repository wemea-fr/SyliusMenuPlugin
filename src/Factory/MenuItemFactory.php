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

namespace Wemea\SyliusMenuPlugin\Factory;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;

final class MenuItemFactory implements MenuItemFactoryInterface
{
    public function __construct(
        private FactoryInterface $decoratedFactory,
    ) {
    }

    /** @psalm-return MenuItemInterface */
    public function createNew(): MenuItemInterface
    {
        /** @var MenuItemInterface $menuItem */
        $menuItem = $this->decoratedFactory->createNew();

        return $menuItem;
    }

    public function createForMenu(MenuInterface $menu): MenuItemInterface
    {
        $menuItem = $this->createNew();
        $menuItem->setMenu($menu);

        return $menuItem;
    }
}
