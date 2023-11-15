<?php

declare(strict_types=1);

namespace spec\Wemea\SyliusMenuPlugin\Entity;

use Wemea\SyliusMenuPlugin\Entity\MenuTranslation;
use Wemea\SyliusMenuPlugin\Entity\MenuTranslationInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Component\Resource\Model\TranslationInterface;

class MenuTranslationSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(MenuTranslation::class);
    }

    function it_implements_interface(): void
    {
        $this->shouldImplement(MenuTranslationInterface::class);
    }

    function it_resolves_dependencies(): void
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
}
