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

namespace spec\Wemea\SyliusMenuPlugin\Entity;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuLink;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkTranslationInterface;
use Wemea\SyliusMenuPlugin\Model\MenuLink as BaseMenuLink;

class MenuLinkSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->setCurrentLocale('en_US');
        $this->setFallbackLocale('en_US');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(MenuLink::class);
    }

    function it_implements_interface(): void
    {
        $this->shouldImplement(MenuLinkInterface::class);
    }

    function it_resolves_dependencies(): void
    {
        $this->shouldImplement(ResourceInterface::class);
        $this->shouldHaveType(BaseMenuLink::class);
    }

    function it_does_not_have_id_by_default(): void
    {
        $this->getId()->shouldBeNull();
    }

    function it_is_translatable(): void
    {
        $this->shouldImplement(TranslatableInterface::class);
    }

    function it_use_translation_as_link_resource(): void
    {
        $this->getLinkProperties()->shouldBeArray();
        $this->getLinkProperties()->shouldContain('translations');
    }

    function it_add_translation_to_latest_link_resources(): void
    {
        $this->getLinkProperties()->shouldReturn([
            'product',
            'taxon',
            'translations',
        ]);
    }

    function it_resolve_translations_as_resource(MenuLinkTranslationInterface $customLinkTranslation): void
    {
        //$this->getType()->shouldBeNull();

        $this->setLinkResource('translations', $customLinkTranslation);
        $this->getType()->shouldReturn('translations');
        $this->getLinkResource()->shouldReturn($customLinkTranslation);
    }
}
