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

namespace Wemea\SyliusMenuPlugin\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Wemea\SyliusMenuPlugin\Helper\MenuLinkPathResolverInterface;

class MenuLinkExtension extends AbstractExtension
{
    public function __construct(
        protected MenuLinkPathResolverInterface $linkPathResolver,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('wemea_sylius_menu_link_path', [$this->linkPathResolver, 'resolveLinkPath']),
        ];
    }
}
