<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Unit\PluginUnitTest\Command;

use Wemea\SyliusMenuPlugin\Command\CreateMenuCommand;
use Wemea\SyliusMenuPlugin\Entity\Menu;
use Wemea\SyliusMenuPlugin\Helper\CreateMenuHelper;
use Wemea\SyliusMenuPlugin\Helper\CreateMenuHelperInterface;
use Wemea\SyliusMenuPlugin\Repository\MenuRepository;
use Wemea\SyliusMenuPlugin\Repository\MenuRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class CreateMenuCommandTest extends TestCase
{
    protected const DEFAULT_ROUTE = '/admin/menu/1/edit';

    protected const COMMAND_NAME = 'wemea:menu:create';

    /** @var MenuRepositoryInterface|MockObject */
    protected $menuRepositoryMock;

    /** @var RouterInterface|MockObject */
    protected $routerMock;

    /** @var CreateMenuHelperInterface|MockObject */
    protected $createMenuHelperMock;

    /** @var CommandTester */
    protected $commandTester;

    public function setUp(): void
    {
        $this->generateMocks();

        $application = new Application();
        $command = new CreateMenuCommand(
            $this->menuRepositoryMock,
            $this->routerMock,
            $this->createMenuHelperMock
        );
        $application->add($command);
        $definedCommand = $application->find(self::COMMAND_NAME);
        $this->commandTester = new CommandTester($definedCommand);
    }

    /**
     * @test
     */
    public function executeCommandSuccess()
    {
        //the menu not already exist
        $this
            ->menuRepositoryMock
            ->method('findByCode')
            ->willReturn(null);

        $returnCode = $this->commandTester->execute([
            'code' => 'new_menu',
        ]);

        $this->assertEquals(
            0,
            $returnCode,
            sprintf('Expected to have 0 as retrun code (Success). Command return : %d', $returnCode)
        );

        $this->assertStringContainsString(
            'The menu new_menu is created.',
            $this->commandTester->getDisplay()
        );

        $this->assertStringContainsString(
            'Go on "' . self::DEFAULT_ROUTE . '" to edit it',
            $this->commandTester->getDisplay()
        );
    }

    /**
     * @test
     */
    public function executeCommandMenuAlreadyExist()
    {
        //the menu not already exist
        $this
            ->menuRepositoryMock
            ->method('findByCode')
            ->willReturn(new Menu());

        $returnCode = $this->commandTester->execute([
            'code' => 'new_menu',
        ]);

        $this->assertEquals(
            1,
            $returnCode,
            sprintf('Expected to have 1 as retrun code (Error). Command return : %d', $returnCode)
        );

        $this->assertStringContainsString(
            'The menu "new_menu" already exist',
            $this->commandTester->getDisplay()
        );
    }

    protected function generateMocks(): void
    {
        $this->menuRepositoryMock = $this->getMockBuilder(MenuRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->routerMock = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->routerMock
            ->method('generate')
            ->willReturn(self::DEFAULT_ROUTE);

        $this->createMenuHelperMock = $this->getMockBuilder(CreateMenuHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->createMenuHelperMock
            ->method('createMenuFromCommandOption')
            ->willReturn(new Menu());
    }
}
