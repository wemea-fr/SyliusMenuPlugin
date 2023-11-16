<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Helper;

use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class MenuLinkPathResolverHelper implements MenuLinkPathResolverInterface
{

    public function __construct(
        protected RouterInterface $router,
        protected array $resourceRouteConfiguration
    ) {
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
            /** @psalm-suppress PossiblyNullArgument */
            Assert::notNull($path, sprintf('No path defined for the menu item link : %s', $owner->getTitle()));

            return $path;
        }

        //else resolve the route
        Assert::keyExists($this->resourceRouteConfiguration, $resourceType, sprintf('Not configuration found for the resource %s', $resourceType));

        /** @var array $routeConfiguration */
        $routeConfiguration = $this->resourceRouteConfiguration[$resourceType];
        $routeParameters = [];
        /**
         * @var string $parameter
         * @var string $method
         */
        foreach ($routeConfiguration['parameters'] as $parameter => $method) {
            /**
             * @phpstan-ignore-next-line
             * @psalm-suppress MixedAssignment
             * @psalm-suppress MixedMethodCall
             * @psalm-suppress PossiblyInvalidMethodCall
             */
            $routeParameters[$parameter] = $resource->$method();
        }

        /** @var string $route */
        $route = $routeConfiguration['route'];
        return $this->router->generate(
            $route,
            $routeParameters,
        );
    }
}
