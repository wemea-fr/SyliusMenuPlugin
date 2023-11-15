<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use Wemea\SyliusMenuPlugin\Model\MenuItem;
use Wemea\SyliusMenuPlugin\Model\VisibilityTraitInterface;
use Faker\Factory;
use Faker\Generator;
use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

final class MenuFixture extends AbstractResourceFixture
{
    /** @var string */
    protected $defaultLocale;

    /** @var Generator */
    protected $faker;

    public function __construct(EntityManagerInterface $objectManager, ExampleFactoryInterface $exampleFactory, string $defaultLocale)
    {
        parent::__construct($objectManager, $exampleFactory);
        $this->defaultLocale = $defaultLocale;
        $this->faker = Factory::create();
    }

    /**
     * @inheritdoc
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $node = $resourceNode->children();

        $node->scalarNode('code')
            ->isRequired()
            ->cannotBeEmpty()
        ;

        $node->booleanNode('enabled');

        $node->enumNode('visibility')
            ->values(VisibilityTraitInterface::VISIBILITY_CODE)
            ->cannotBeEmpty()
        ;

        $node->arrayNode('translations')
                ->useAttributeAsKey('locale')
                ->cannotBeEmpty()
                ->defaultValue($this->getDefaultTranslations())
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('title')
                        ->cannotBeEmpty()
        ;

        $node->arrayNode('channels')->scalarPrototype();

        $node->append($this->getItemsNodeDefinition());
    }

    public function getName(): string
    {
        return 'menu';
    }

    protected function getDefaultTranslations(): array
    {
        return [
            $this->defaultLocale => [
                    'title' => $this->faker->words(3, true),
                ],
        ];
    }

    /**
     * @psalm-suppress UnusedVariable
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress UnusedMethodCall
     * @psalm-suppress PossiblyUndefinedMethod
     */
    protected function getItemsNodeDefinition(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('items');

        /** @phpstan-ignore-next-line */
        return $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('items')
                    ->cannotBeEmpty()
                    ->arrayPrototype()
                        ->children()
                            ->enumNode('target')
                                ->defaultValue(MenuItem::TARGET_SELF)
                                ->treatNullLike(MenuItem::TARGET_SELF)
                                ->values(MenuItem::AVAILABLE_TARGETS)
                            ->end()

                            ->integerNode('priority')->defaultNull()
                            ->end()

                            ->scalarNode('css_classes')->defaultNull()
                            ->end()

                            ->arrayNode('translations')
                                ->cannotBeEmpty()
                                ->useAttributeAsKey('locale')
                                ->defaultValue($this->getDefaultTranslations())
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('title')->cannotBeEmpty()
                                        ->isRequired()
                                        ->end()

                                        ->scalarNode('description')
                                            ->cannotBeEmpty()
                                            ->defaultNull()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()

                            //add link definition
                            ->append($this->getLinkDefinition())
                ->end()
            ->end()
        ;
    }

    /**
     * @psalm-suppress UnusedVariable
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress UnusedMethodCall
     * @psalm-suppress PossiblyUndefinedMethod
     */
    protected function getLinkDefinition(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('link');

        /** @phpstan-ignore-next-line */
        return $treeBuilder->getRootNode()
            ->isRequired()
            // FIXME: how to force to have one of this defined
            ->children()
            //defined configuration for custom link
                ->arrayNode('custom_link')
                    ->cannotBeEmpty()
                    ->useAttributeAsKey('locale')
                    ->scalarPrototype()->cannotBeEmpty()->end()
                ->end()

                //configuration for Product link
                ->scalarNode('product_code')
                    ->defaultNull()
                    ->cannotBeEmpty()
                ->end()

                //configuration for taxon
                ->scalarNode('taxon_code')
                    ->defaultNull()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;
    }
}
