<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

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
