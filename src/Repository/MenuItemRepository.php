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

namespace Wemea\SyliusMenuPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;

class MenuItemRepository extends EntityRepository implements MenuItemRepositoryInterface
{
    public function createQueryBuilderByMenuId(string $menuId): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.menu', 'm')
            ->where('m.id = :menu_id')
            ->setParameter('menu_id', $menuId)
        ;
    }

    public function findOneByIdAndMenuId($id, $menuId): ?MenuItemInterface
    {
        /** @var MenuItemInterface|null $menuItem */
        $menuItem = $this->createQueryBuilder('i')
            ->where('i.menu = :menuId')
            ->andWhere('i.id = :id')
            ->setParameter('menuId', $menuId)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $menuItem;
    }
}
