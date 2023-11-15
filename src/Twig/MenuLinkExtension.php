<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Twig;

use Wemea\SyliusMenuPlugin\Helper\MenuLinkPathResolverInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuLinkExtension extends AbstractExtension
{
    /** @var MenuLinkPathResolverInterface */
    protected $linkPathResolver;

    public function __construct(
        MenuLinkPathResolverInterface $linkPathResolver,
    ) {
        $this->linkPathResolver = $linkPathResolver;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('wemea_sylius_menu_link_path', [$this->linkPathResolver, 'resolveLinkPath']),
        ];
    }
}
