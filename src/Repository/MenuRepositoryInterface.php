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

namespace Wemea\SyliusMenuPlugin\Repository;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuInterface;

interface MenuRepositoryInterface extends RepositoryInterface
{
    public function findByCode(string $code): ?MenuInterface;

    public function findOneEnabledByCodeAndChannel(string $code, ChannelInterface $channel): ?MenuInterface;
}
