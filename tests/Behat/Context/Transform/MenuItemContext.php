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
use Doctrine\ORM\EntityManagerInterface;
use Webmozart\Assert\Assert;

class MenuItemContext implements Context
{
    /** @var EntityManagerInterface */
    protected $menuItemManager;

    /** @var class-string */
    protected $menuClassName;

    public function __construct(
        EntityManagerInterface $menuItemManager,
        $menuClassName,
    ) {
        $this->menuItemManager = $menuItemManager;
        $this->menuClassName = $menuClassName;
    }

    /**
     * @Transform /^"([^"]+)" item$/
     */
    public function getItemByTitle(string $title)
    {
        $items = $this->findItemByTitle($title);
        Assert::eq(
            count($items),
            1,
            sprintf('%d items has been found with title "%s".', count($items), $title),
        );

        return $items[0];
    }

    /**
     * Create query here and not use repository because this function is used only for test
     * therefore is not relevant to add it at the MenuItemRepository
     */
    protected function findItemByTitle(string $title, string $locale = 'en_US'): array
    {
        return $this->menuItemManager
                ->createQueryBuilder()
                ->select('i')
                ->from($this->menuClassName, 'i')
                ->innerJoin('i.translations', 'translation', 'WITH', 'translation.locale = :locale')
                ->andWhere('translation.title = :title')
                ->setParameter('title', $title)
                ->setParameter('locale', $locale)
                ->getQuery()
                ->getResult()
        ;
    }
}
