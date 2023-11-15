<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Wemea\SyliusMenuPlugin\Model\MenuLinkInterface as BaseMenuLinkInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface MenuLinkInterface extends BaseMenuLinkInterface, ResourceInterface, TranslatableInterface
{
    public const CUSTOM_LINK_PROPERTY = 'translations';

    public function getCustomLink(): ?string;

    public function setCustomLink(?string $link): void;
}
