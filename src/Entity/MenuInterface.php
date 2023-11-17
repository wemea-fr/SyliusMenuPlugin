<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Wemea\SyliusMenuPlugin\Model\MenuInterface as BaseMenuInterface;

interface MenuInterface extends ResourceInterface, TranslatableInterface, BaseMenuInterface
{
    public function getTitle(): ?string;

    public function setTitle(?string $title): void;

    public function getMenuItemsOrderByPriority(): array;
}
