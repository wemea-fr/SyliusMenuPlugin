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

namespace Wemea\SyliusMenuPlugin\Factory;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;

interface MenuItemFactoryInterface extends FactoryInterface
{
    public function createForMenu(MenuInterface $menu): MenuItemInterface;
}
