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

namespace Tests\Wemea\SyliusMenuPlugin\IntegrationTest\Command;

class DeleteMenuCommandTest extends AbstractCommandTest
{
    protected const COMMAND_NAME = 'wemea:menu:delete';

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
