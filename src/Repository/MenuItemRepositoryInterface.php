<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface MenuItemRepositoryInterface extends RepositoryInterface
{
    public function createQueryBuilderByMenuId(string $menuId): QueryBuilder;

    /**
     * @param int|string $id
     * @param int|string $menuId
     */
    public function findOneByIdAndMenuId($id, $menuId): ?MenuItemInterface;
}