<?php

declare(strict_types=1);

namespace spec\Wemea\SyliusMenuPlugin\Entity;

use Wemea\SyliusMenuPlugin\Entity\Menu;
use Wemea\SyliusMenuPlugin\Entity\MenuInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Model\Menu as BaseMenu;
use Wemea\SyliusMenuPlugin\Model\MenuInterface as BaseMenuInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

class MenuSpec extends ObjectBehavior
{
    /**
     * constructor to init default locale
     */
    function let(): void
    {
        $this->setCurrentLocale('en_US');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Menu::class);
    }

    function it_implements_interfaces(): void
    {
        $this->shouldImplement(MenuInterface::class);
    }

    function it_resolves_depedencies(): void
    {
        $this->shouldHaveType(BaseMenu::class);
        $this->shouldImplement(BaseMenuInterface::class);
        $this->shouldImplement(ResourceInterface::class);
        $this->shouldImplement(TranslatableInterface::class);
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

    //Business logic is test with unit test
    function orderMenuItemsReturnArray(MenuItemInterface $menuItem1, MenuItemInterface $menuItem2): void
    {
        $this->addMenuItem($menuItem1);
        $this->addMenuItem($menuItem2);

        $this->getMenuItemsOrderByPriority()->shouldBeArray();
    }
}
