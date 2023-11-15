<?php

declare(strict_types=1);

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
