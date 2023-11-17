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

/**
 * Trait to manage visibility.
 * This trait can be used to add visibility at MenuItems if is necessary
 */
trait VisibilityTrait
{
    /** @var int|null */
    protected $visibility = VisibilityTraitInterface::PUBLIC_VISIBILITY;

    public function getVisibility(): ?int
    {
        return $this->visibility;
    }

    public function setVisibility(?int $visibility): void
    {
        $this->visibility = $visibility;
    }

    public function makeItPublic(): void
    {
        $this->setVisibility(VisibilityTraitInterface::PUBLIC_VISIBILITY);
    }

    public function makeItPrivate(): void
    {
        $this->setVisibility(VisibilityTraitInterface::PRIVATE_VISIBILITY);
    }
}
