<?php

declare(strict_types=1);

namespace spec\Wemea\SyliusMenuPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Wemea\SyliusMenuPlugin\Model\Menu;
use Wemea\SyliusMenuPlugin\Model\MenuInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Model\VisibilityTraitInterface;

final class MenuSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Menu::class);
    }

    function it_implements_interface(): void
    {
        $this->shouldImplement(MenuInterface::class);
        $this->shouldImplement(ToggleableInterface::class);
        $this->shouldImplement(VisibilityTraitInterface::class);
    }

    function it_does_not_hav_code_by_default(): void
    {
        $this->getCode()->shouldBeNull();
    }

    function its_code_is_mutable(): void
    {
        $this->setCode('default_menu');
        $this->getCode()->shouldReturn('default_menu');
    }

    function it_is_enabled_by_default(): void
    {
        $this->isEnabled()->shouldReturn(true);
    }

    function its_states_is_mutable(): void
    {
        //test with setter
        $this->setEnabled(true);

        //test with specific methods
        $this->disable();
        $this->isEnabled()->shouldReturn(false);

        $this->enable();
        $this->isEnabled()->shouldReturn(true);
    }

    function it_is_public_by_default(): void
    {
        $this->getVisibility()->shouldReturn(VisibilityTraitInterface::PUBLIC_VISIBILITY);
    }

    function its_visibility_is_mutable(): void
    {
        //test with method
        $this->makeItPrivate();
        $this->getVisibility()->shouldReturn(VisibilityTraitInterface::PRIVATE_VISIBILITY);

        $this->makeItPublic();
        $this->getVisibility()->shouldReturn(VisibilityTraitInterface::PUBLIC_VISIBILITY);

        //test with setter
        $this->setVisibility(2);
        $this->getVisibility()->shouldReturn(VisibilityTraitInterface::PRIVATE_VISIBILITY);
    }

    function it_does_not_have_item_by_default(): void
    {
        $this->getMenuItems()->shouldBeLike(new ArrayCollection());
    }

    function its_menu_items_are_mutable(MenuItemInterface $menuItem1, MenuItemInterface $menuItem2): void
    {
        $this->setItems(new ArrayCollection([$menuItem1->getWrappedObject(), $menuItem2->getWrappedObject()]));

        $this->getMenuItems()->shouldBeLike(new ArrayCollection([$menuItem1->getWrappedObject(), $menuItem2->getWrappedObject()]));
    }

    function it_possible_to_add_menu_items(MenuItemInterface $menuItem1, MenuItemInterface $menuItem2): void
    {
        $this->addMenuItem($menuItem1);
        $this->getMenuItems()->shouldBeLike(new ArrayCollection([$menuItem1->getWrappedObject()]));
        $this->addMenuItem($menuItem2);
        $this->getMenuItems()->shouldBeLike(new ArrayCollection([$menuItem1->getWrappedObject(), $menuItem2->getWrappedObject()]));
    }

    function it_is_possible_to_remove_menu_item(MenuItemInterface $menuItem1, MenuItemInterface $menuItem2): void
    {
        $this->setItems(new ArrayCollection([$menuItem1->getWrappedObject(), $menuItem2->getWrappedObject()]));

        $this->removeMenuItem($menuItem2);
        $this->getMenuItems()->shouldBeLike(new ArrayCollection([$menuItem1->getWrappedObject()]));
    }

    function it_checks_if_has_items(MenuItemInterface $menuItem): void
    {
        $this->hasMenuItems()->shouldReturn(false);

        $this->addMenuItem($menuItem);

        $this->hasMenuItems()->shouldReturn(true);
    }

    function it_does_not_have_channel_by_default(): void
    {
        $this->getChannels()->shouldBeLike(new ArrayCollection());
    }

    function it_possible_to_add_channel(ChannelInterface $channel): void
    {
        $this->addChannel($channel);
        $this->getChannels()->shouldBeLike(new ArrayCollection([$channel->getWrappedObject()]));
    }

    function it_possible_to_remove_channels(ChannelInterface $channel1, ChannelInterface $channel2): void
    {
        //set default state
        $this->addChannel($channel1);
        $this->addChannel($channel2);
        $this->getChannels()->shouldBeLike(new ArrayCollection([$channel1->getWrappedObject(), $channel2->getWrappedObject()]));

        //try to remove
        $this->removeChannel($channel2);
        $this->getChannels()->shouldBeLike(new ArrayCollection([$channel1->getWrappedObject()]));
    }
}
