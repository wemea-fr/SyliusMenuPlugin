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
use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkTranslation;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkTranslationInterface;

class MenuLinkTranslationSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(MenuLinkTranslation::class);
    }

    function it_implements_interface(): void
    {
        $this->shouldImplement(MenuLinkTranslationInterface::class);
    }

    function it_is_a_tranlation_model(): void
    {
        $this->shouldHaveType(AbstractTranslation::class);
        $this->shouldImplement(TranslationInterface::class);
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_does_not_have_id_by_default(): void
    {
        $this->getId()->shouldBeNull();
    }

    function it_does_not_have_custom_link_by_default(): void
    {
        $this->getCustomLink()->shouldBeNull();
    }

    function its_custom_link_is_mutable(): void
    {
        $this->setCustomLink('/my/link');
        $this->getCustomLink()->shouldReturn('/my/link');
    }
}
