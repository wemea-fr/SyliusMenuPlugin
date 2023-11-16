<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

interface MenuInterface extends ToggleableInterface, VisibilityTraitInterface
{
    public function getCode(): ?string;

    public function setCode(?string $code): void;

    public function addMenuItem(?MenuItemInterface $menuItem): void;

    public function removeMenuItem(?MenuItemInterface $menuItem): void;

    public function setItems(Collection $menuItems): void;

    public function getMenuItems(): Collection;

    public function hasMenuItems(): bool;

    public function getChannels(): Collection;

    public function addChannel(ChannelInterface $channel): void;

    public function removeChannel(ChannelInterface $channel): void;
}
