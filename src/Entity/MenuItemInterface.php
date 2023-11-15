<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Wemea\SyliusMenuPlugin\Model\MenuItemInterface as BaseMenuItemInterface;
use Sylius\Component\Core\Model\ImageAwareInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface MenuItemInterface extends BaseMenuItemInterface, ResourceInterface, TranslatableInterface, ImageAwareInterface
{
    public function getTitle(): ?string;

    public function setTitle(?string $title): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    public function getImage(): ?ImageInterface;

    public function setImage(?ImageInterface $image): void;

    public function hasImage(): bool;
}
