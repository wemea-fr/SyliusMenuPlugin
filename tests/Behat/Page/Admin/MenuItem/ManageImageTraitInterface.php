<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\MenuItem;

interface ManageImageTraitInterface
{
    public function attachImage(string $image): void;

    public function pressRemoveImageButton(): void;

    public function removeButtonIsVisible(): bool;

    public function imagePreviewIsVisible(): bool;

    public function isIconImageField(): bool;
}
