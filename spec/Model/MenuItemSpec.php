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
use Wemea\SyliusMenuPlugin\Model\MenuInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItem;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Model\MenuLinkInterface;

class MenuItemSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(MenuItem::class);
    }

    function its_implement_interface(): void
    {
        $this->shouldImplement(MenuItemInterface::class);
    }

    function it_does_no_have_target_by_default(): void
    {
        $this->getTarget()->shouldBeNull();
    }

    function its_target_is_mutable()
    {
        $this->setTarget('_blank');
        $this->getTarget()->shouldReturn('_blank');
    }

    function it_does_not_have_css_class_by_default(): void
    {
        $this->getCssClasses()->shouldBeNull();
    }

    function its_css_class_are_mutable(): void
    {
        $this->setCssClasses('red, bold');
        $this->getCssClasses()->shouldReturn('red, bold');
    }

    function it_does_not_have_priority_by_default(): void
    {
        $this->getPriority()->shouldBeNull();
    }

    function its_priority_is_mutable(): void
    {
        $this->setPriority(10);
        $this->getPriority()->shouldReturn(10);
    }

    function it_does_not_have_link_by_default(): void
    {
        $this->getLink()->shouldBeNull();
    }

    function its_link_is_mutable(MenuLinkInterface $link): void
    {
        $this->setLink($link);
        $this->getLink()->shouldBeAnInstanceOf(MenuLinkInterface::class);
        $this->getLink()->shouldReturn($link);
    }

    function it_check_if_has_link(MenuLinkInterface $link): void
    {
        $this->hasLink()->shouldReturn(false);
        $this->setLink($link);
        $this->hasLink()->shouldReturn(true);
    }

    function it_does_not_have_menu_by_default(): void
    {
        $this->getMenu()->shouldBeNull();
    }

    function its_menu_is_mutable(MenuInterface $menu): void
    {
        $this->setMenu($menu);
        $this->getMenu()->shouldReturn($menu);
    }
}
