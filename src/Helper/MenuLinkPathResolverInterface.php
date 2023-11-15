<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Helper;

use Wemea\SyliusMenuPlugin\Entity\MenuLinkInterface;

interface MenuLinkPathResolverInterface
{
    public function resolveLinkPath(MenuLinkInterface $menuLink): string;
}
