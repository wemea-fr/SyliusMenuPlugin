<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Helper;

use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class MenuLinkPathResolverHelper implements MenuLinkPathResolverInterface
{
    /** @var RouterInterface */
    protected $router;

    /** @var array */
    protected $resourceRouteConfiguration;

    public function __construct(RouterInterface $router, array $resourceRouteConfiguration)
    {
        $this->router = $router;
        $this->resourceRouteConfiguration = $resourceRouteConfiguration;
    }

    public function resolveLinkPath(MenuLinkInterface $menuLink): string
    {
        $resourceType = $menuLink->getType();
        $resource = $menuLink->getLinkResource();
        //Check if the resource is correctly defined
        Assert::notNull($resourceType);

        if ($resourceType === MenuLinkInterface::CUSTOM_LINK_PROPERTY) {
            $path = $menuLink->getCustomLink();
            /** @var MenuItemInterface $owner */
            $owner = $menuLink->getOwner();
            Assert::notNull($path, sprintf('No path defined for the menu item link : %s', $owner->getTitle()));

            return $path;
        }

        //else resolve the route
        Assert::keyExists($this->resourceRouteConfiguration, $resourceType, sprintf('Not configuration found for the resource %s', $resourceType));

        $routeConfiguration = $this->resourceRouteConfiguration[$resourceType];
        $routeParameters = [];
        foreach ($routeConfiguration['parameters'] as $parameter => $method) {
            /** @phpstan-ignore-next-line */
            $routeParameters[$parameter] = $resource->$method();
        }

        return $this->router->generate(
            $routeConfiguration['route'],
            $routeParameters,
        );
    }
}
