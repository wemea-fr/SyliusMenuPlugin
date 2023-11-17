<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Factory;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Model\MenuLinkInterface;

interface MenuLinkFactoryInterface extends FactoryInterface
{
    public function createNew(): MenuLinkInterface;

    public function createForMenuItem(MenuItemInterface $menuItem): MenuLinkInterface;
}
