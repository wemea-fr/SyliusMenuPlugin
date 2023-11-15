<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Factory;

use Wemea\SyliusMenuPlugin\Model\MenuInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class MenuFactory implements MenuFactoryInterface
{
    /** @var FactoryInterface */
    protected $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

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

        foreach ($channels as $channel) {
            $menu->addChannel($channel);
        }

        return $menu;
    }
}
