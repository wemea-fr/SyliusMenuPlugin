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
