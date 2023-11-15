<?php

declare(strict_types=1);

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
