<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Factory;

use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Model\MenuLinkInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface MenuLinkFactoryInterface extends FactoryInterface
{
    public function createNew(): MenuLinkInterface;

    public function createForMenuItem(MenuItemInterface $menuItem): MenuLinkInterface;
}
