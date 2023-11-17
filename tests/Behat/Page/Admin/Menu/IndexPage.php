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

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\Admin\Crud\IndexPage as BaseIndexPage;
use Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\ColumnIndexPageTrait;

class IndexPage extends BaseIndexPage implements IndexPageInterface
{
    use ColumnIndexPageTrait;

    public function isButtonAction(string $buttonName): bool
    {
        try {
            $this->getButtonAction($buttonName);

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function isSimpleButton(string $buttonName): bool
    {
        $action = $this->getElement('action_button_cell');
        $linksElements = $action->findAll('css', 'a');

        foreach ($linksElements as $linkElement) {
            if (preg_match('/' . $buttonName . '/i', $linkElement->getText())) {
                return true;
            }
        }

        return false;
    }

    public function isFilter(string $filter): bool
    {
        try {
            $this->getElement('filter_element', ['%filter%' => $filter]);

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function ButtonHasOption($buttonName, $dropdownOption): bool
    {
        $button = $this->getButtonAction($buttonName);
        $buttonOptions = $button->getParent()->findAll('css', '.item');
        $buttonOptions = array_map(
            function ($option) {
                return trim($option->getText());
            },
            $buttonOptions,
        );

        return in_array($dropdownOption, $buttonOptions);
    }

    public function clickOnActionButton(string $option, string $menuCode): void
    {
        $actionsElement = $this->getActionsForResource(['code' => $menuCode]);
        $linksElements = $actionsElement->findAll('css', 'a');

        foreach ($linksElements as $linkElement) {
            if (preg_match('/' . $option . '/i', $linkElement->getText())) {
                $linkElement->click();

                return;
            }
        }

        //if button is not used throw error
        throw new ElementNotFoundException($this->getDriver());
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'action_button_cell' => 'td[data-test-actions]',
            'filter_element' => '[name^="criteria[%filter%]"]',
        ]);
    }

    /**
     * FIXME: there is better way to do this ?
     *
     * @throws ElementNotFoundException
     */
    protected function getButtonAction(string $buttonName): NodeElement
    {
        $actionCell = $this->getElement('action_button_cell');

        $buttons = $actionCell->findAll('css', '.button');

        $filteringButton = array_filter($buttons, function ($button) use ($buttonName) {
            $textElement = $button->find('css', '.text');
            if (null === $textElement) {
                return false;
            }

            return trim($textElement->getText()) === $buttonName;
        });

        if (empty($filteringButton)) {
            throw new ElementNotFoundException($this->getDriver());
        }

        return array_shift($filteringButton);
    }
}
