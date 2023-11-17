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

namespace Wemea\SyliusMenuPlugin\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Wemea\SyliusMenuPlugin\Entity\MenuInterface;
use Wemea\SyliusMenuPlugin\Factory\MenuFactoryInterface;

class CreateMenuHelper implements CreateMenuHelperInterface
{
    /** @var class-string */
    protected $channelClassName;

    /**
     * @param class-string $channelClassName
     */
    public function __construct(
        protected MenuFactoryInterface $menuFactory,
        protected EntityManagerInterface $channelManager,
        $channelClassName,
    ) {
        $this->channelClassName = $channelClassName;
    }

    public function createMenuFromCommandOption(SymfonyStyle $io, string $code, bool $disabled, bool $private, ?string $channelsCode): MenuInterface
    {
        //resolves channels
        if ($channelsCode !== null) {
            $channelsCodesAsArray = explode(',', $channelsCode);    //parse code
            $channelsCodesAsArray = array_map(function (string $code): string {     // trim each code
                return trim($code);
            }, $channelsCodesAsArray);

            $channels = $this->getChannelsFromCodes($channelsCodesAsArray);

            //FIXME : find better warning message ?
            if (count($channelsCodesAsArray) !== count($channels)) {
                $io->warning(sprintf('One ore more channels not found in %s', $channelsCode));
            }
        } else {
            $channels = $this->channelManager
                ->getRepository($this->channelClassName)
                ->findAll()
            ;
        }

        /** @var MenuInterface $menu */
        $menu = $this->menuFactory->createWithCodeStateVisibilityAndChannels($code, $disabled, $private, $channels);

        return $menu;
    }

    /**
     * Do it here to avoid to add trait to ChannelRepository
     *
     * @return ChannelInterface[]
     */
    protected function getChannelsFromCodes(array $channelsCode): array
    {
        /** @var ChannelInterface[] $channels */
        $channels = $this->channelManager
            ->createQueryBuilder()
            ->select('c')
            ->from($this->channelClassName, 'c')
            ->where('c.code IN (:codes)')
            ->setParameter('codes', $channelsCode)
            ->getQuery()
            ->getResult()
        ;

        return $channels;
    }
}
