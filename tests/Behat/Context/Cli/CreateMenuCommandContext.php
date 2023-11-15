<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Context\Cli;

use Wemea\SyliusMenuPlugin\Command\CreateMenuCommand;
use Wemea\SyliusMenuPlugin\Entity\MenuInterface;
use Wemea\SyliusMenuPlugin\Helper\CreateMenuHelperInterface;
use Wemea\SyliusMenuPlugin\Repository\MenuRepositoryInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class CreateMenuCommandContext extends AbstractCliContext
{
    /** @var MenuRepositoryInterface */
    protected $menuRepository;

    /** @var RouterInterface */
    protected $router;

    /** @var CreateMenuHelperInterface */
    protected $createMenuHelper;

    public function __construct(
        MenuRepositoryInterface $menuRepository,
        RouterInterface $router,
        CreateMenuHelperInterface $createMenuHelper,
        KernelInterface $kernel,
    ) {
        $this->menuRepository = $menuRepository;
        $this->router = $router;
        $this->createMenuHelper = $createMenuHelper;
        parent::__construct($kernel);
    }

    /**
     * @When /^I run command to create the menu (\w+)$/
     * @When /^I run command to create the menu (\w+) with specified "([^"]*)" as channel$/
     */
    public function iRunCommandToCreateTheMenu(string $menuCode, ?string $channelCode = null)
    {
        $commandParameters = [
            'code' => $menuCode,
        ];

        if (null !== $channelCode) {
            $commandParameters['--channels'] = $channelCode;
        }
        $this->executeCommand($commandParameters);
    }

    /**
     * @Then /^I should be notified the menu was successfully created$/
     */
    public function iShouldBeNotifiedTheMenuWasSuccessfullyCreated()
    {
        //Assert equals to 1 (preg match return a int on success)
        Assert::eq(
            preg_match('/The menu (\w)+ is created/', $this->commandTester->getDisplay()),
            1,
            sprintf("The output not contain \"The menu <menu_code> is created\". \nCurrent output : \n %s", $this->commandTester->getDisplay()),
        );
    }

    /**
     * @Given /^(the menu "[^"]+") should be enabled on (\w+) channel$/
     * @Given /^(the menu "[^"]+") should be enabled on (\w+) and (\w+) channels$/
     */
    public function theMenuIsEnabledOnChannel(MenuInterface $menu, ...$expectedChannels)
    {
        $channelCode = [];
        foreach ($menu->getChannels() as $channel) {
            $channelCode[] = $channel->getCode();
        }

        foreach ($expectedChannels as $expectedChannel) {
            Assert::inArray(
                $expectedChannel,
                $channelCode,
                sprintf('This menu is not enabled on "%s" channel', $expectedChannel),
            );
        }
    }

    /**
     * @Given /^(the menu "[^"]+") should not be enabled on (\w+) channel$/
     * @Given /^(the menu "[^"]+") should not be enabled on (\w+) and (\w+) channels$/
     */
    public function theMenuIsNotEnabledOnChannel(MenuInterface $menu, ...$expectedChannels)
    {
        $channelCodes = [];
        foreach ($menu->getChannels() as $channel) {
            $channelCodes[] = $channel->getCode();
        }

        foreach ($expectedChannels as $expectedChannel) {
            Assert::false(
                in_array($expectedChannel, $channelCodes),
                sprintf('This menu is enabled on "%s" channel. Expected not.', $expectedChannel),
            );
        }
    }

    /**
     * @Given /^I should be notified one channel not found with the message "([^"]*)"$/
     */
    public function iShouldBeNotifiedOneChannelNotFoundWithTheMessage(string $expectedMessage)
    {
        //Assert equals to 1 (preg match return a int on success)
        Assert::eq(
            preg_match('/' . $expectedMessage . '/', $this->commandTester->getDisplay()),
            1,
            sprintf("The output not contain \"%s\". \nCurrent output : \n %s", $expectedMessage, $this->commandTester->getDisplay()),
        );
    }

    protected function getCommand()
    {
        return new CreateMenuCommand($this->menuRepository, $this->router, $this->createMenuHelper);
    }

    protected static function getCommandName(): string
    {
        return 'wemea:menu:create';
    }
}
