<?php

declare(strict_types=1);

namespace spec\Wemea\SyliusMenuPlugin\Entity;

use Wemea\SyliusMenuPlugin\Entity\MenuItem;
use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItem as BaseMenuItem;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface as BaseMenuItemInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ImageAwareInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

class MenuItemSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->setCurrentLocale('en_US');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(MenuItem::class);
    }

    function it_implements_interface(): void
    {
        $this->shouldImplement(MenuItemInterface::class);
    }

    function it_resolves_dependencies(): void
    {
        $this->shouldHaveType(BaseMenuItem::class);
        $this->shouldImplement(BaseMenuItemInterface::class);
        $this->shouldImplement(TranslatableInterface::class);
        $this->shouldImplement(ResourceInterface::class);
        $this->shouldImplement(ImageAwareInterface::class);
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

    function its_title_is_translatable(): void
    {
        $this->setTitle('one title');
        $this->setCurrentLocale('fr_FR');
        $this->setTitle('Un titre');

        $this->setCurrentLocale('en_US');
        $this->getTitle()->shouldReturn('one title');

        $this->setCurrentLocale('fr_FR');
        $this->getTitle()->shouldReturn('Un titre');
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

    function its_description_is_translatable(): void
    {
        $this->setDescription('one description');
        $this->setCurrentLocale('fr_FR');
        $this->setDescription('une description');

        $this->setCurrentLocale('en_US');
        $this->getDescription()->shouldReturn('one description');

        $this->setCurrentLocale('fr_FR');
        $this->getDescription()->shouldReturn('une description');
    }

    function it_does_not_have_image_by_default(): void
    {
        $this->getImage()->shouldBeNull();
    }

    function its_image_is_mutable(ImageInterface $image): void
    {
        $this->setImage($image);
        $this->getImage()->shouldReturn($image);
    }

    function it_check_if_has_image(ImageInterface $image): void
    {
        $this->hasImage()->shouldReturn(false);
        $this->setImage($image);
        $this->hasImage()->shouldReturn(true);
    }
}
