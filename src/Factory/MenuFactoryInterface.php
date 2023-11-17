<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Factory;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuInterface;

interface MenuFactoryInterface extends FactoryInterface
{
    public function createNew(): MenuInterface;

    public function createWithCodeStateVisibilityAndChannels(string $code, bool $disabled, bool $private, array $channels): MenuInterface;
}
