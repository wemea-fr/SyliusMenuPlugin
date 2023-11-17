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

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

interface CreatePageInterface extends BaseCreatePageInterface, ManageImageTraitInterface
{
    public function isField(string $field): bool;

    public function isLinkField(string $fieldCode): bool;

    public function isTranslatableField(string $field, string $localeCode): bool;

    public function isCustomLinkFieldIn(string $localeCode): bool;

    public function linkFieldIsVisible(string $field): bool;

    public function setFieldValue(string $field, string $value): void;

    public function setLinkFieldValue(string $field, string $value): void;

    public function setTranslatableFieldValue(string $field, string $value, string $localeCode): void;

    public function setProductLink(ProductInterface $product): void;

    public function setTaxonLink(TaxonInterface $taxon): void;

    public function setCustomLinkUrl(string $value, string $localeCode): void;

    public function getErrorMessageOfTranslatableField(string $field, string $localeCode): string;

    public function getTargetResourceLinkErrorMessage(): string;

    public function getErrorMessageOfCustomLinkField(string $locale): string;
}
