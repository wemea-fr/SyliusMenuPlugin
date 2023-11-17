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

use Sylius\Behat\Page\Admin\Crud\IndexPageInterface as BaseIndexPageInterface;
use Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\ColumnIndexPageTraitInterface;

interface IndexPageInterface extends BaseIndexPageInterface, ColumnIndexPageTraitInterface
{
    public function isElementOnBreadcrumb(?string $elementName): bool;

    public function clickOnParentMenuLink(): void;

    public function isCreateButton(): bool;

    public function clickOnCreateButton(): void;
}
