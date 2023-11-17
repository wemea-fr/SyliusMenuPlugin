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

use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;
use Wemea\SyliusMenuPlugin\Command\DeleteMenuCommand;
use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Repository\MenuRepositoryInterface;

class DeleteCommandContext extends AbstractCliContext
{
    /** @var MenuRepositoryInterface */
    protected $menuRepository;

    public function __construct(MenuRepositoryInterface $menuRepository, KernelInterface $kernel)
    {
        $this->menuRepository = $menuRepository;
        parent::__construct($kernel);
    }

    /**
     * @Given I run command to delete :menuCode
     */
    public function iRunCommandToDelete(string $menuCode)
    {
        //execute command without interact
        $this->executeCommand(['code' => $menuCode], ['interactive' => false]);
    }

    /**
     * @Given /^the menu (\w+)( should not)? appear on the database$/
     */
    public function theMenuAppearOnTheDatabase(string $menuCode, bool $notAppear = false)
    {
        if ($notAppear) {
            Assert::null(
                $this->menuRepository->findByCode($menuCode),
                'Menu appear on Database. Expected not',
            );
        } else {
            Assert::notNull(
                $this->menuRepository->findByCode($menuCode),
                'The menu not found on Database',
            );
        }
    }

    /**
     * @Given /^(this menu item) appear on database$/
     */
    public function thisMenuItemAppearOnDatabase(MenuItemInterface $menuItem)
    {
        dump($menuItem->getId());
    }

    protected function getCommand()
    {
        return new DeleteMenuCommand($this->menuRepository);
    }

    protected static function getCommandName(): string
    {
        return 'wemea:menu:delete';
    }
}
