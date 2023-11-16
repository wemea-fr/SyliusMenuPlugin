<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Factory;

use Sylius\Component\Core\Model\ChannelInterface;
use Wemea\SyliusMenuPlugin\Model\MenuInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class MenuFactory implements MenuFactoryInterface
{

    public function __construct(
        protected FactoryInterface $decoratedFactory
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
