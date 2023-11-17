<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Wemea\SyliusMenuPlugin\Model\MenuInterface;

/**
 * @method findOneBy(array $criteria, array $orderBy = null): ?MenuInterface
 */
class MenuRepository extends EntityRepository implements MenuRepositoryInterface
{
    public function findByCode(string $code): ?MenuInterface
    {
        return $this->findOneBy(['code' => $code]);
    }

    public function findOneEnabledByCodeAndChannel(string $code, ChannelInterface $channel): ?MenuInterface
    {
        /** @var MenuInterface|null $menu */
        $menu = $this->createQueryBuilder('m')
            ->where('m.code = :code')
            ->setParameter('code', $code)
            ->andWhere('m.enabled = 1')
            ->innerJoin('m.channels', 'channel')
            ->andWhere('channel = :channel')
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $menu;
    }
}
