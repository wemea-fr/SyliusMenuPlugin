<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Factory;

use Wemea\SyliusMenuPlugin\Model\MenuInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class MenuItemFactory implements MenuItemFactoryInterface
{

    public function __construct(
        private FactoryInterface $decoratedFactory
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
