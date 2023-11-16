<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Controller;

use Wemea\SyliusMenuPlugin\Model\VisibilityTraitInterface;
use Wemea\SyliusMenuPlugin\Repository\MenuRepositoryInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

final class RenderMenuController
{
    public const DEFAULT_MENU_TEMPLATE = '@WemeaSyliusMenuPlugin/Shop/Menu/_default.html.twig';

    public function __construct(
        private Environment $templatingEngine,
        private MenuRepositoryInterface $menuRepository,
        private ShopperContextInterface $shopperContext,
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function renderAction(string $code, string $template = self::DEFAULT_MENU_TEMPLATE): Response
    {
        $channel = $this->shopperContext->getChannel();

        $menu = $this->menuRepository->findOneEnabledByCodeAndChannel($code, $channel);

        if ($menu !== null && $menu->getVisibility() === VisibilityTraitInterface::PRIVATE_VISIBILITY) {
            if ($this->authorizationChecker->isGranted('ROLE_USER') == false) {
                //if menu is private and user not logged => set menu to null
                $menu = null;
            }
        }

        $content = $this->templatingEngine->render(
            $template,
            ['menu' => $menu],
        );

        return new Response($content);
    }
}
