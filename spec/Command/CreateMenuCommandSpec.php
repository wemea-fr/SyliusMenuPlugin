<?php

declare(strict_types=1);

namespace spec\Wemea\SyliusMenuPlugin\Command;

use Wemea\SyliusMenuPlugin\Command\CreateMenuCommand;
use Wemea\SyliusMenuPlugin\Helper\CreateMenuHelperInterface;
use Wemea\SyliusMenuPlugin\Repository\MenuRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Routing\RouterInterface;

class CreateMenuCommandSpec extends ObjectBehavior
{
    function let(
        MenuRepositoryInterface $menuRepository,
        RouterInterface $router,
        CreateMenuHelperInterface $createMenuHelper,
    ): void {
        $this->beConstructedWith($menuRepository, $router, $createMenuHelper);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CreateMenuCommand::class);
    }

    function it_accept_private_as_option(): void
    {
        $def = $this->getDefinition();
        $def->hasOption('private')->shouldReturn(true);
        //check shortcut
        $def->getOption('private')->getShortcut()->shouldReturn('p');
        //check if is InputOption::VALUE_NONE
        $def->getOption('private')->acceptValue()->shouldReturn(false);
    }

    function it_accept_disabled_as_option(): void
    {
        $def = $this->getDefinition();
        $def->hasOption('disabled')->shouldReturn(true);
        //check shortcut
        $def->getOption('disabled')->getShortcut()->shouldReturn('d');
        //check if is InputOption::VALUE_NONE
        $def->getOption('disabled')->acceptValue()->shouldReturn(false);
    }

    function it_accept_channels_as_option(): void
    {
        $def = $this->getDefinition();
        $def->hasOption('channels')->shouldReturn(true);
        //check shortcut
        $def->getOption('channels')->getShortcut()->shouldReturn('c');
        //check if is InputOption::VALUE_NONE
        $def->getOption('channels')->isValueRequired()->shouldReturn(true);
    }
}
