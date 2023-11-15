<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Model;

class MenuItem implements MenuItemInterface
{
    public const TARGET_SELF = '_self';

    public const TARGET_BLANK = '_blank';

    public const AVAILABLE_TARGETS = [
        self::TARGET_SELF,
        self::TARGET_BLANK,
    ];

    /** @var string|null */
    protected $target;

    /** @var string|null */
    protected $cssClasses;

    /** @var int|null */
    protected $priority;

    /** @var MenuInterface|null */
    protected $menu;

    /** @var MenuLinkInterface|null */
    protected $link;

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(?string $target): void
    {
        $this->target = $target;
    }

    public function getCssClasses(): ?string
    {
        return  $this->cssClasses;
    }

    public function setCssClasses(?string $cssClasses): void
    {
        $this->cssClasses = $cssClasses;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): void
    {
        $this->priority = $priority;
    }

    public function getLink(): ?MenuLinkInterface
    {
        return $this->link;
    }

    public function hasLink(): bool
    {
        return $this->link !== null;
    }

    public function setLink(?MenuLinkInterface $link): void
    {
        $this->link = $link;
    }

    public function getMenu(): ?MenuInterface
    {
        return $this->menu;
    }

    public function setMenu(?MenuInterface $menu): void
    {
        $this->menu = $menu;
    }
}
