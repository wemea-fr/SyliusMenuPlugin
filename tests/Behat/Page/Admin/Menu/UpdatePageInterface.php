<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\Menu;

use Sylius\Behat\Page\Admin\Crud\UpdatePageInterface as BaseUpdatePage;

interface UpdatePageInterface extends BaseUpdatePage
{
    public function isField(string $expectedField): bool;

    public function isTranslatableFieldInLocale(string $field, string $localeCode): bool;

    public function theFieldIsDisabled(string $field): bool;

    public function setValueOfTranslatableField(string $value, string $field, string $localeCode): void;

    public function getErrorMessageOfTranslatableField(string $field, string $localeCode): string;

    public function getFieldValue(string $field): string;

    public function selectVisibilityOption(string $value): void;

    public function checkEnabled(bool $check);

    public function getTranslatableFieldValue(string $field, string $localeCode): string;
}
