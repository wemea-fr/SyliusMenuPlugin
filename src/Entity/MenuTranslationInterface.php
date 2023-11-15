<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface MenuTranslationInterface extends TranslationInterface, ResourceInterface
{
    public function getTitle(): ?string;

    public function setTitle(?string $title): void;
}
