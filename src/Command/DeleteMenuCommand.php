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
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Repository\MenuRepositoryInterface;

class DeleteMenuCommand extends Command
{
    protected static $defaultName = 'wemea:menu:delete';

    public function __construct(
        protected MenuRepositoryInterface $menuRepository,
        string $name = null,
    ) {
        parent::__construct($name);

        $this->menuRepository = $menuRepository;
    }

    protected function configure(): void
    {
        $this->setDescription('Delete an existing menu');
        $this->setHelp('This command allow to remove an existing menu from the code');

        $this->addArgument(
            'code',
            InputArgument::REQUIRED,
            'The unique code of the menu',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        /** @var string $code */
        $code = $input->getArgument('code');

        //Ask validation to remove menu
        if (!$this->canExecute($input, $output, $code)) {
            $io->success('Nothing to do.');

            return 0;
        }

        /** @var MenuItemInterface|null $menu */
        $menu = $this->menuRepository->findByCode($code);

        //Add warning if menu not exist.
        if (null === $menu) {
            $io->warning('No menu found. Nothing to do');

            return 0;
        }

        //Delete menu
        $this->menuRepository->remove($menu);
        $io->success('The menu was removed');

        return 0;
    }

    protected function getQuestionMessage(string $menuCode): string
    {
        return '<error>' .
            'Warning! If you remove menu, some data can be loss. ' .
            'Are you sure to remove "' . $menuCode . '" menu ? [y/N] ' .
            '</error>';
    }

    /**
     * Custom function to remove menu if :
     *  is not interaction command (--no-interaction option)
     *  user validate the message to remove menu
     */
    protected function canExecute(InputInterface $input, OutputInterface $output, string $menuCode): bool
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion($this->getQuestionMessage($menuCode), false);

        return !$input->isInteractive() || (bool) $helper->ask($input, $output, $question);
    }
}
