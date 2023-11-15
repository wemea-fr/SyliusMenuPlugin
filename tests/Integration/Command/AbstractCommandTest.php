<?php

declare(strict_types=1);

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
