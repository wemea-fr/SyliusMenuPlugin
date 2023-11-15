<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\MenuItem;

use Sylius\Behat\Page\Admin\Crud\IndexPageInterface as BaseIndexPageInterface;
use Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\ColumnIndexPageTraitInterface;

interface IndexPageInterface extends BaseIndexPageInterface, ColumnIndexPageTraitInterface
{
    public function isElementOnBreadcrumb(?string $elementName): bool;

    public function clickOnParentMenuLink(): void;

    public function isCreateButton(): bool;

    public function clickOnCreateButton(): void;
}
