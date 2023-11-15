<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Model;

interface MenuItemInterface
{
    public function getTarget(): ?string;

    public function setTarget(?string $target): void;

    public function getCssClasses(): ?string;

    public function setCssClasses(?string $cssClasses): void;

    public function getPriority(): ?int;

    public function setPriority(?int $priority): void;

    public function getLink(): ?MenuLinkInterface;

    public function hasLink(): bool;

    public function setLink(?MenuLinkInterface $link): void;

    public function getMenu(): ?MenuInterface;

    public function setMenu(?MenuInterface $menu): void;
}
