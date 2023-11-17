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
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItemInterface;

interface MenuItemRepositoryInterface extends RepositoryInterface
{
    public function createQueryBuilderByMenuId(string $menuId): QueryBuilder;

    /**
     * @param int|string $id
     * @param int|string $menuId
     */
    public function findOneByIdAndMenuId($id, $menuId): ?MenuItemInterface;
}
