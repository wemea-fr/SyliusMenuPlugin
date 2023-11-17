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
