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

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuInterface;

class MenuFactory implements MenuFactoryInterface
{
    public function __construct(
        protected FactoryInterface $decoratedFactory,
    ) {
    }

    /** @psalm-return MenuInterface */
    public function createNew(): MenuInterface
    {
        /** @var MenuInterface $menu */
        $menu = $this->decoratedFactory->createNew();

        return $menu;
    }

    public function createWithCodeStateVisibilityAndChannels(string $code, bool $disabled, bool $private, array $channels): MenuInterface
    {
        $menu = $this->createNew();

        $menu->setCode($code);

        if ($disabled) {
            $menu->disable();
        }

        if ($private) {
            $menu->makeItPrivate();
        }

        /** @var ChannelInterface $channel */
        foreach ($channels as $channel) {
            $menu->addChannel($channel);
        }

        return $menu;
    }
}
