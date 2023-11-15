<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $submenu = $menu->getChild('content_management');

        // create only if not exist to avoid conflicts with another plugin
        if (null === $submenu) {
            $submenu = $menu
                ->addChild('content_management')
                ->setLabel('wemea_sylius_menu.ui.content_management')
            ;
        }

        $submenu
            ->addChild('menu', ['route' => 'wemea_sylius_menu_admin_menu_index'])
            ->setLabel('wemea_sylius_menu.ui.menu')
            ->setLabelAttribute('icon', 'th list')
        ;
    }
}
