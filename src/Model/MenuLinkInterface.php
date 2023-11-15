<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Model;

use InvalidArgumentException;

interface MenuLinkInterface
{
    public function getType(): ?string;

    public function getOwner(): ?MenuItemInterface;

    public function setOwner(?MenuItemInterface $owner): void;

    public static function getLinkProperties(): array;

    /**
     * @param string|object|null $value
     *
     * @throws InvalidArgumentException
     */
    public function setLinkResource(string $currentProperty, $value): void;

    /**
     * @return string|object|null
     */
    public function getLinkResource();
}
