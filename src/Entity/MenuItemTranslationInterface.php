<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface MenuItemTranslationInterface extends TranslationInterface, ResourceInterface
{
    public function getTitle(): ?string;

    public function setTitle(?string $title): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;
}
