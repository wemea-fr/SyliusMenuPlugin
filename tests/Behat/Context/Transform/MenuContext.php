<?php

declare(strict_types=1);

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
