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

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\MenuItem;

interface ManageImageTraitInterface
{
    public function attachImage(string $image): void;

    public function pressRemoveImageButton(): void;

    public function removeButtonIsVisible(): bool;

    public function imagePreviewIsVisible(): bool;

    public function isIconImageField(): bool;
}
