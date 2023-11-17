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

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuInterface;
use Wemea\SyliusMenuPlugin\Model\VisibilityTraitInterface;

class MenuContext implements Context
{
    /** @var FactoryInterface */
    protected $menuFactory;

    /** @var EntityManagerInterface */
    protected $menuManager;

    /** @var SharedStorageInterface */
    protected $sharedStorage;

    public function __construct(
        FactoryInterface $menuFactory,
        EntityManagerInterface $menuManager,
        SharedStorageInterface $sharedStorage,
    ) {
        $this->menuFactory = $menuFactory;
        $this->menuManager = $menuManager;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given /^the store has one menu$/
     * @Given the store has one menu with code :code
     * @Given the store has one menu with code :code and :title as title
     * @Given the store has one menu with code :code and :title as title in :locale
     */
    public function theStoreHasOneMenu(string $code = 'default menu', string $title = 'default title', string $locale = 'en_US')
    {
        $menu = $this->createMenu($code);
        $menu->setCurrentLocale($locale);
        $menu->setTitle($title);
        $this->saveMenu($menu);
    }

    /**
     * @Given /^(this menu) is (enabled|disabled) on (this channel)$/
     */
    public function thisMenuIsEnabledOnThisChannel(MenuInterface $menu, string $state, ChannelInterface $channel)
    {
        $menuChannels = $menu->getChannels();

        if ($state === 'enabled' && !$menuChannels->contains($channel)) {
            $menu->addChannel($channel);
        }

        if ($state === 'disabled' && $menuChannels->contains($channel)) {
            $menu->removeChannel($channel);
        }

        $this->saveMenu($menu);
    }

    /**
     * @Given /^(this menu) is (disabled|enabled)$/
     */
    public function thisMenuIsDisabled(MenuInterface $menu, string $state)
    {
        if ($state === 'disabled') {
            $menu->disable();
        } else {
            $menu->enable();
        }
        $this->saveMenu($menu);
    }

    /**
     * @Given /^(this menu) is (public|private)$/
     */
    public function thisMenuIsPublic(MenuInterface $menu, string $visibility)
    {
        if ($visibility === 'public') {
            $menu->makeItPublic();
        } else {
            $menu->makeItPrivate();
        }

        $this->saveMenu($menu);
    }

    protected function createMenu(
        string $code,
        bool $enabled = true,
        ?int $visibility = VisibilityTraitInterface::PUBLIC_VISIBILITY,
    ): MenuInterface {
        /** @var MenuInterface $menu */
        $menu = $this->menuFactory->createNew();
        $menu->setCode($code);
        $menu->setEnabled($enabled);
        $menu->setVisibility($visibility);

        return $menu;
    }

    protected function saveMenu(MenuInterface $menu)
    {
        $this->menuManager->persist($menu);
        $this->menuManager->flush();
        $this->sharedStorage->set('menu', $menu);
    }
}
