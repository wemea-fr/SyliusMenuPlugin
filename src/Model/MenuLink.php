<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Model;

use InvalidArgumentException;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Webmozart\Assert\Assert;

class MenuLink implements MenuLinkInterface
{
    /** @var ProductInterface|null */
    protected $product;

    /** @var TaxonInterface|null */
    protected $taxon;

    /** @var MenuItemInterface|null */
    protected $owner;

    public function getType(): ?string
    {
        foreach ($this::getLinkProperties() as $property) {
            /** @phpstan-ignore-next-line */
            if ($this->$property !== null) {
                return $property;
            }
        }

        return null;
    }

    public function getOwner(): ?MenuItemInterface
    {
        return $this->owner;
    }

    public function setOwner(?MenuItemInterface $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * Function to list property to resolve link
     * Return property name (and use it as accessor/code)
     * To add new type make : array_merge(
     *                              parent::getLinkProperties(),
     *                             ['your_property']);
     */
    public static function getLinkProperties(): array
    {
        return [
            'product',
            'taxon',
        ];
    }

    /**
     * Set the current Link
     * Save value and reset other links
     *
     * @param string|object|null $value
     *
     * @throws InvalidArgumentException
     */
    public function setLinkResource(string $currentProperty, $value): void
    {
        /** @phpstan-ignore-next-line */
        Assert::inArray(
            $currentProperty,
            $this::getLinkProperties(),
            sprintf('The property \'%s\' not exist', $currentProperty),
        );

        foreach ($this::getLinkProperties() as $property) {
            if ($currentProperty === $property) {
                /** @phpstan-ignore-next-line */
                $this->$property = $value;
            } else {
                /** @phpstan-ignore-next-line */
                $this->$property = null;
            }
        }
    }

    /**
     * @return string|object|null
     */
    public function getLinkResource()
    {
        $type = $this->getType();
        if (null === $type) {
            return null;
        }
        /** @phpstan-ignore-next-line */
        return $this->$type;
    }
}
