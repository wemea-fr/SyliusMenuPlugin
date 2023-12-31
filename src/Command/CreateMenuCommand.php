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

namespace Wemea\SyliusMenuPlugin\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;
use Wemea\SyliusMenuPlugin\Helper\CreateMenuHelperInterface;
use Wemea\SyliusMenuPlugin\Repository\MenuRepositoryInterface;

class CreateMenuCommand extends Command
{
    public const EDIT_MENU_ROUTE_NAME = 'wemea_sylius_menu_admin_menu_update';

    protected static $defaultName = 'wemea:menu:create';

    public function __construct(
        protected MenuRepositoryInterface $menuRepository,
        protected RouterInterface $router,
        protected CreateMenuHelperInterface $createMenuHelper,
        string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription('Generate new menu editable on the BO');
        $this->setHelp('This command allow to create a new administrable menu for store\'s administrators');

        $this->addArgument(
            'code',
            InputArgument::REQUIRED,
            'The unique code of the menu',
        );

        $this->addOption(
            'disabled',
            'd',
            InputOption::VALUE_NONE,
            'Disabled the menu. Menu is enabled by default',
        );

        $this->addOption(
            'private',
            'p',
            InputOption::VALUE_NONE,
            'Make this menu private. Is public by default',
        );

        $this->addOption(
            'channels',
            'c',
            InputOption::VALUE_REQUIRED,
            'List channels code (separated by comma) where the menu should be enabled. If not set, menu is enabled on all channels by defaults.',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        /** @var string $code */
        $code = $input->getArgument('code');

        //check the unique constraint
        if ($this->menuRepository->findByCode($code) !== null) {
            $io->error(sprintf('The menu "%s" already exist', $code));

            return 1;
        }

        /** @var bool $isDisabled */
        $isDisabled = $input->getOption('disabled');

        /** @var bool $isPrivate */
        $isPrivate = $input->getOption('private');

        /** @var string|null $channels */
        $channels = $input->getOption('channels');

        $menu = $this->createMenuHelper->createMenuFromCommandOption(
            $io,
            $code,
            $isDisabled,
            $isPrivate,
            $channels,
        );

        $this->menuRepository->add($menu);

        $route = $this->router->generate(self::EDIT_MENU_ROUTE_NAME, ['id' => $menu->getId()]);
        $io->success(sprintf('The menu %s is created.', $code));

        $io->writeln([
            sprintf('Go on "%s" to edit it', $route),
            '',
            ]);

        return 0;
    }
}
