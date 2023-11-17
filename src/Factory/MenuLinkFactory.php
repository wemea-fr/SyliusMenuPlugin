<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Factory;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Model\MenuLinkInterface;

final class MenuLinkFactory implements MenuLinkFactoryInterface
{
    public function __construct(
        private FactoryInterface $decoratedFactory,
    ) {
    }

    /** @psalm-return MenuLinkInterface */
    public function createNew(): MenuLinkInterface
    {
        /** @var MenuLinkInterface $link */
        $link = $this->decoratedFactory->createNew();

        return $link;
    }

    public function createForMenuItem(MenuItemInterface $menuItem): MenuLinkInterface
    {
        $link = $this->createNew();
        $link->setOwner($menuItem);

        return $link;
    }
}
