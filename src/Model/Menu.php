<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class Menu implements MenuInterface
{
    use ToggleableTrait;
    use VisibilityTrait;
    use TimestampableTrait;

    /** @var string|null */
    protected $code;

    /** @var ArrayCollection|Collection|MenuItemInterface[] */
    protected $menuItems;

    /** @var ArrayCollection|Collection|ChannelInterface[] */
    protected $channels;

    public function __construct()
    {
        $this->menuItems = new ArrayCollection();
        $this->channels = new ArrayCollection();
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function addMenuItem(?MenuItemInterface $menuItem): void
    {
        $this->menuItems->add($menuItem);
    }

    public function removeMenuItem(?MenuItemInterface $menuItem): void
    {
        $this->menuItems->removeElement($menuItem);
    }

    public function setItems(Collection $menuItems): void
    {
        $this->menuItems = $menuItems;
    }

    public function getMenuItems(): Collection
    {
        return $this->menuItems;
    }

    public function hasMenuItems(): bool
    {
        return !$this->menuItems->isEmpty();
    }

    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(ChannelInterface $channel): void
    {
        $this->channels->add($channel);
    }

    public function removeChannel(ChannelInterface $channel): void
    {
        $this->channels->removeElement($channel);
    }
}