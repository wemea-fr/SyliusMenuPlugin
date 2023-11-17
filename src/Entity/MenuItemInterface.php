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

use Sylius\Component\Core\Model\ImageAwareInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface as BaseMenuItemInterface;

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
