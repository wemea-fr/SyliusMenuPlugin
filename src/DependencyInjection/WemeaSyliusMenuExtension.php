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

namespace Wemea\SyliusMenuPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class WemeaSyliusMenuExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    protected const DEFAULT_RESOURCE_PATH_RESOLVER_CONFIGURATION = [
        'custom' => [
            'route' => null,
            'parameters' => [],
        ],
        'product' => [
            'route' => 'sylius_shop_product_show',
            'parameters' => [
                'slug' => 'getSlug',
            ],
        ],
        'taxon' => [
            'route' => 'sylius_shop_product_index',
            'parameters' => [
                'slug' => 'getSlug',
            ],
        ],
    ];

    /**
     * @psalm-suppress UnusedVariable
     * @psalm-suppress MixedArgument
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        /** @psalm-suppress PossiblyNullArgument */
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        //FIXME : is the good way to do this ? Prefer do it on configuration file ?
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $resourcePathResolverConfiguration = array_merge(
            self::DEFAULT_RESOURCE_PATH_RESOLVER_CONFIGURATION,
            $config['resource_path_resolver_configuration'],
        );

        $container->setParameter('wemea_sylius_menu.resource_path_resolver_configuration', $resourcePathResolverConfiguration);
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependDoctrineMigrations($container);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'DoctrineMigrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@WemeaSyliusMenuPlugin/migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [
            'Sylius\Bundle\CoreBundle\Migrations',
        ];
    }
}
