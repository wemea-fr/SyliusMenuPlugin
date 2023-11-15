<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\MenuItem;

use Sylius\Behat\Page\Admin\Crud\UpdatePageInterface as BaseUpdatePageInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

interface UpdatePageInterface extends BaseUpdatePageInterface, ManageImageTraitInterface
{
    public function isField(string $field): bool;

    public function isLinkField(string $fieldCode): bool;

    public function isTranslatableField(string $field, string $localeCode): bool;

    public function linkFieldIsVisible(string $field): bool;

    public function setFieldValue(string $field, string $value): void;

    public function setLinkFieldValue(string $field, string $value): void;

    public function setTranslatableFieldValue(string $field, string $value, string $localeCode): void;

    public function setProductLink(ProductInterface $product): void;

    public function setTaxonLink(TaxonInterface $taxon): void;

    public function getLinkFieldValue(string $field): string;

    public function getErrorMessageOfTranslatableField(string $field, string $localeCode): string;

    public function getTargetResourceLinkErrorMessage(): string;

    public function isCustomLinkFieldIn(string $localeCode): bool;

    public function setCustomLinkUrl(string $value, string $localeCode): void;

    public function getCustomLinkFieldValue(string $localeCode): string;

    public function getErrorMessageOfCustomLinkField(string $locale): string;
}
