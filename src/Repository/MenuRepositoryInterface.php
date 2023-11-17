<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Repository;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuInterface;

interface MenuRepositoryInterface extends RepositoryInterface
{
    public function findByCode(string $code): ?MenuInterface;

    public function findOneEnabledByCodeAndChannel(string $code, ChannelInterface $channel): ?MenuInterface;
}
