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

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin;

trait ColumnIndexPageTrait
{
    public function getColumnsNames(): array
    {
        $table = $this->getElement('table');
        $thElements = $table->findAll('css', 'th');

        return array_map(function ($element) {
            return trim($element->getText());
        }, $thElements);
    }
}
