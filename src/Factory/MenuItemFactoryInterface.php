<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Factory;

use Wemea\SyliusMenuPlugin\Model\MenuInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface MenuItemFactoryInterface extends FactoryInterface
{
    public function createForMenu(MenuInterface $menu): MenuItemInterface;
}
