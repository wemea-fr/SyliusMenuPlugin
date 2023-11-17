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

namespace Wemea\SyliusMenuPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Wemea\SyliusMenuPlugin\Model\MenuLinkInterface as BaseMenuLinkInterface;

interface MenuLinkInterface extends BaseMenuLinkInterface, ResourceInterface, TranslatableInterface
{
    public const CUSTOM_LINK_PROPERTY = 'translations';

    public function getCustomLink(): ?string;

    public function setCustomLink(?string $link): void;
}
