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

namespace Wemea\SyliusMenuPlugin\Controller;

use Sylius\Component\Core\Context\ShopperContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;
use Wemea\SyliusMenuPlugin\Model\VisibilityTraitInterface;
use Wemea\SyliusMenuPlugin\Repository\MenuRepositoryInterface;

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
