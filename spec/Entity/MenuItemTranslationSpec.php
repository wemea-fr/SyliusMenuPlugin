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
use Sylius\Component\Resource\Model\TranslationInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuItemTranslation;
use Wemea\SyliusMenuPlugin\Entity\MenuItemTranslationInterface;

class MenuItemTranslationSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(MenuItemTranslation::class);
    }

    function it_implements_interface(): void
    {
        $this->shouldImplement(MenuItemTranslationInterface::class);
    }

    function it_is_a_translation_model(): void
    {
        $this->shouldHaveType(AbstractTranslation::class);
        $this->shouldImplement(TranslationInterface::class);
    }

    function it_does_not_have_id_by_default(): void
    {
        $this->getId()->shouldBeNull();
    }

    function it_does_not_have_title_by_default(): void
    {
        $this->getTitle()->shouldBeNull();
    }

    function its_title_is_mutable(): void
    {
        $this->setTitle('one title');
        $this->getTitle()->shouldReturn('one title');
    }

    function it_does_not_have_description_by_default(): void
    {
        $this->getDescription()->shouldBeNull();
    }

    function its_description_is_mutable(): void
    {
        $this->setDescription('one description');
        $this->getDescription()->shouldReturn('one description');
    }
}
