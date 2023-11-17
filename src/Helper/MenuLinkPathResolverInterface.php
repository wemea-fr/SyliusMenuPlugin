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

namespace Wemea\SyliusMenuPlugin\Helper;

use Wemea\SyliusMenuPlugin\Entity\MenuLinkInterface;

interface MenuLinkPathResolverInterface
{
    public function resolveLinkPath(MenuLinkInterface $menuLink): string;
}
