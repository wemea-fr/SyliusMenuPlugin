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

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Exception\CommandNotFoundException;

abstract class AbstractCommandTest extends KernelTestCase
{
    /** @var Application */
    protected $application;

    protected function setUp(): void
    {
        //Load container interface
        $kernel = static::createKernel();
        $this->application = new Application($kernel);
    }

    protected function commandExist(string $command): bool
    {
        try {
            $this->application->find($command);

            return true;
        } catch (CommandNotFoundException $exception) {
            return false;
        }
    }
}
