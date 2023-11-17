<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Factory;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;

interface MenuItemFactoryInterface extends FactoryInterface
{
    public function createForMenu(MenuInterface $menu): MenuItemInterface;
}
