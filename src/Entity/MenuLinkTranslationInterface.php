<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface MenuLinkTranslationInterface extends TranslationInterface, ResourceInterface
{
    public function getCustomLink(): ?string;

    public function setCustomLink(?string $link): void;
}
