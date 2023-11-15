<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Factory;

use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Model\MenuLinkInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class MenuLinkFactory implements MenuLinkFactoryInterface
{
    /** @var FactoryInterface */
    private $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

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
