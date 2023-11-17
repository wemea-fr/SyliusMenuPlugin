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

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Context\Cli;

use Behat\Behat\Context\Context;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

// FIXME : Is better to implement Context on child class ?
abstract class AbstractCliContext implements Context
{
    /** @var null */
    protected $application;

    /** @var CommandTester|null */
    protected $commandTester;

    public function __construct(KernelInterface $kernel)
    {
        $application = new Application($kernel);
        $command = $this->getCommand();
        $application->add($command);

        $this->application = $application;
        //init command tester to null
        $this->commandTester = null;
    }

    abstract protected function getCommand();

    abstract protected static function getCommandName(): string;

    protected function executeCommand(array $parameters = [], array $option = []): void
    {
        $command = $this->application->find($this::getCommandName());
        $this->commandTester = new CommandTester($command);
        $this->commandTester->execute($parameters, $option);
    }
}
