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

interface VisibilityTraitInterface
{
    public const PUBLIC_VISIBILITY = 1;

    public const PRIVATE_VISIBILITY = 2;

    public const VISIBILITY_CODE = [
        self::PUBLIC_VISIBILITY => 'public',
        self::PRIVATE_VISIBILITY => 'private',
    ];

    public function setVisibility(?int $visibility): void;

    public function getVisibility(): ?int;

    public function makeItPublic(): void;

    public function makeItPrivate(): void;
}
