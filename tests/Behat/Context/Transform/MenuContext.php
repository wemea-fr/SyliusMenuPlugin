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

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;
use Wemea\SyliusMenuPlugin\Repository\MenuRepositoryInterface;

class MenuContext implements Context
{
    /** @var MenuRepositoryInterface */
    protected $menuRepository;

    public function __construct(MenuRepositoryInterface $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * @Transform /^the menu "([^"]+)"$/
     * @Transform /^"([^"]+)" menu$/
     */
    public function getMenuByCode(string $code)
    {
        $menu = $this->menuRepository->findByCode($code);
        Assert::notNull(
            $menu,
            sprintf('The menu with code "%s" not found', $code),
        );

        return $menu;
    }
}
