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

namespace spec\Wemea\SyliusMenuPlugin\Model;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Model\MenuLink;
use Wemea\SyliusMenuPlugin\Model\MenuLinkInterface;

class MenuLinkSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(MenuLink::class);
    }

    function it_implements_interface(): void
    {
        $this->shouldImplement(MenuLinkInterface::class);
    }

    function it_does_not_have_type_by_default(): void
    {
        $this->getType()->shouldBeNull();
    }

    function it_does_not_have_owner_by_default(): void
    {
        $this->getOwner()->shouldBeNull();
    }

    function it_owner_is_mutable(MenuItemInterface $menuItem): void
    {
        $this->setOwner($menuItem);
        $this->getOwner()->shouldReturn($menuItem);
    }

    function it_resolves_link_setter(ProductInterface $product, TaxonInterface $taxon): void
    {
        $this->setLinkResource('product', $product);
        $this->getType()->shouldReturn('product');

        $this->setLinkResource('taxon', $taxon);
        $this->getType()->shouldReturn('taxon');
    }

    function it_does_not_have_resource_by_default(): void
    {
        $this->getLinkResource()->shouldBeNull();
    }

    function it_resolves_current_resource(ProductInterface $product, TaxonInterface $taxon): void
    {
        //it return product as product link
        $this->setLinkResource('product', $product);
        $this->getLinkResource()->shouldReturn($product);

        //it return taxon as taxon link
        $this->setLinkResource('taxon', $taxon);
        $this->getLinkResource()->shouldReturn($taxon);
    }

    function it_throw_exception_if_setter_type_is_invalid(ResourceInterface $resource): void
    {
        $this->shouldThrow(new \InvalidArgumentException('The property \'invalid_resource\' not exist'))->during('setLinkResource', ['invalid_resource', $resource]);
    }
}
