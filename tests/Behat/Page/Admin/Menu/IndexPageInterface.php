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

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\Menu;

use Sylius\Behat\Page\Admin\Crud\IndexPageInterface as BaseIndexPageInterface;
use Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\ColumnIndexPageTraitInterface;

interface IndexPageInterface extends BaseIndexPageInterface, ColumnIndexPageTraitInterface
{
    public function isButtonAction(string $buttonName): bool;

    public function ButtonHasOption(string $buttonName, string $dropdownOption): bool;

    public function clickOnActionButton(string $option, string $menuCode): void;

    public function isSimpleButton(string $buttonName): bool;

    public function isFilter(string $filter): bool;
}
