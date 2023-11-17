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

namespace Tests\Wemea\SyliusMenuPlugin\Unit\PluginUnitTest\Command;

use Wemea\SyliusMenuPlugin\Command\DeleteMenuCommand;
use Wemea\SyliusMenuPlugin\Entity\Menu;
use Wemea\SyliusMenuPlugin\Repository\MenuRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DeleteMenuCommandTest extends TestCase
{
    protected const COMMAND_NAME = 'wemea:menu:delete';

    protected const DEFAULT_MENU_CODE = 'default_menu';

    protected const SUCCESS_RETURN_CODE = 0;

    /** @var MenuRepositoryInterface|MockObject */
    protected $menuRepositoryMock;

    /** @var CommandTester */
    protected $commandTester;

    protected function setUp(): void
    {
        $this->menuRepositoryMock = $this->getMockBuilder(MenuRepositoryInterface::class)
                                        ->disableOriginalConstructor()
                                        ->getMock();

        $application = new Application();
        $command = new DeleteMenuCommand($this->menuRepositoryMock);
        $application->add($command);
        $definedCommand = $application->find(self::COMMAND_NAME);
        $this->commandTester = new CommandTester($definedCommand);
    }

    /**
     * @test
     */
    public function deleteMenuCommandWithInteractSuccess(): void
    {
        $this->menuRepositoryMock
            ->method('findByCode')
            ->willReturn(new Menu());

        //prepare interact
        $this->commandTester->setInputs(['y']);

        //execute command
        $this->commandTester->execute([
            'code' => self::DEFAULT_MENU_CODE,
        ]);

        $this->assertCommandSuccess();

        //Assert command use interact
        $this->assertCommandOutputContainsMessage('Warning! If you remove menu, some data can be loss. Are you sure to remove "' . self::DEFAULT_MENU_CODE . '" menu ? [y/N]');

        //assert success
        $this->assertCommandOutputContainsMessage(
            '[OK] The menu was removed'
        );
    }

    /**
     * @test
     */
    public function deleteMenuCommandWithInteractMenuNotFound(): void
    {
        $this->menuRepositoryMock
            ->method('findByCode')
            ->willReturn(null);

        //prepare interact
        $this->commandTester->setInputs(['y']);

        //execute command
        $this->commandTester->execute([
            'code' => self::DEFAULT_MENU_CODE,
        ]);

        $this->assertCommandSuccess();

        $this->assertCommandOutputContainsMessage(
            '[WARNING] No menu found. Nothing to do'
        );
    }

    /**
     * @test
     */
    public function deleteMenuCommandRefuseToRemove(): void
    {
        $this->menuRepositoryMock
            ->method('findByCode')
            ->willReturn(new Menu());

        //prepare interact
        $this->commandTester->setInputs(['N']);

        //execute command
        $this->commandTester->execute([
            'code' => self::DEFAULT_MENU_CODE,
        ]);

        $this->assertCommandSuccess();

        $this->assertCommandOutputContainsMessage(
            '[OK] Nothing to do'
        );
    }

    /**
     * @test
     */
    public function deleteMenuCommandUseNoAsDefaultAnswer(): void
    {
        $this->menuRepositoryMock
            ->method('findByCode')
            ->willReturn(new Menu());

        //prepare interact with empty string to simulate only press ENTER key
        $this->commandTester->setInputs(['']);

        //execute command
        $this->commandTester->execute([
            'code' => self::DEFAULT_MENU_CODE,
        ]);

        $this->assertCommandSuccess();

        $this->assertCommandOutputContainsMessage(
            '[OK] Nothing to do'
        );
    }

    /**
     * @test
     */
    public function deleteMenuSuccessWithoutInteract(): void
    {
        $this->menuRepositoryMock
            ->method('findByCode')
            ->willReturn(new Menu());

        //execute command
        $this->commandTester->execute([
            'code' => self::DEFAULT_MENU_CODE,
            ],
            ['interactive' => false]
        );

        $this->assertCommandSuccess();

        //assert command not use interact
        $this->assertCommandOutputNotContainsMessage('Warning! If you remove menu, some data can be loss. Are you sure to remove "' . self::DEFAULT_MENU_CODE . '" menu ? [y/N]');

        //assert command use default behaviour
        $this->assertCommandOutputContainsMessage(
            '[OK] The menu was removed'
        );
    }

    protected function assertCommandSuccess(): void
    {
        $this->assertEquals(
            self::SUCCESS_RETURN_CODE,
            $this->commandTester->getStatusCode(),
            sprintf('On success, the command should return 0 but this command return %d.', $this->commandTester->getStatusCode())
        );
    }

    protected function assertCommandOutputContainsMessage(string $expectedMessage): void
    {
        $this->assertStringContainsString(
            $expectedMessage,
            $this->commandTester->getDisplay(),
            sprintf("The command not contains : \n\"%s\"\n", $expectedMessage)
        );
    }

    protected function assertCommandOutputNotContainsMessage(string $expectedMessage): void
    {
        $this->assertStringNotContainsString(
            $expectedMessage,
            $this->commandTester->getDisplay(),
            sprintf("\nThe command output contains : \n\"%s\"\nExpected not\n\n", $expectedMessage)
        );
    }
}
