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

use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\Admin\Crud\IndexPage as BaseIndexPage;
use Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\ColumnIndexPageTrait;
use Webmozart\Assert\Assert;

class IndexPage extends BaseIndexPage implements IndexPageInterface
{
    use ColumnIndexPageTrait;

    public function isElementOnBreadcrumb(?string $elementName): bool
    {
        $breadcrumb = $this->getElement('breadcrumb');
        $breadcrumbItems = $breadcrumb->findAll('css', 'a');

        if (empty($breadcrumbItems)) {
            return false;
        }

        $breadcrumbItems = array_map(
            function ($item) {
                return trim($item->getText());
            },
            $breadcrumbItems,
        );

        return in_array(
            $elementName,
            $breadcrumbItems,
        );
    }

    public function clickOnParentMenuLink(): void
    {
        $breadcrumb = $this->getElement('breadcrumb');
        //by default, the menu parent is the third element
        $breadcrumbItems = $breadcrumb->findAll('css', 'a');
        Assert::true(
            count($breadcrumbItems) >= 3,
        );
        $breadcrumbItems[2]->click();
    }

    public function isCreateButton(): bool
    {
        try {
            $this->getElement('create_button');

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function clickOnCreateButton(): void
    {
        $button = $this->getElement('create_button');
        $button->click();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'breadcrumb' => '.ui.breadcrumb',
            'create_button' => '[data-test-button="sylius.ui.create"]',
        ]);
    }
}
