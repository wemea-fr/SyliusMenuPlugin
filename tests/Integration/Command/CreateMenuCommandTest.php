<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\IntegrationTest\Command;

class CreateMenuCommandTest extends AbstractCommandTest
{
    protected const COMMAND_NAME = 'wemea:menu:create';

    /**
     * @test
     */
    public function commandIsRegister()
    {
        $this->assertTrue(
            $this->commandExist(self::COMMAND_NAME),
            sprintf('The command "%s" is not register. Please verify the plugin integration.', self::COMMAND_NAME)
        );
    }
}