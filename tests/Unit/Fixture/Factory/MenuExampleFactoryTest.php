<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Unit\PluginUnitTest\Fixture\Factory;

use Wemea\SyliusMenuPlugin\Entity\Menu;
use Wemea\SyliusMenuPlugin\Entity\MenuInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuItem;
use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuLink;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkInterface;
use Wemea\SyliusMenuPlugin\Factory\MenuFactory;
use Wemea\SyliusMenuPlugin\Factory\MenuItemFactory;
use Wemea\SyliusMenuPlugin\Factory\MenuLinkFactory;
use Wemea\SyliusMenuPlugin\Fixture\Factory\MenuExampleFactory;
use Wemea\SyliusMenuPlugin\Model\VisibilityTraitInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ChannelBundle\Doctrine\ORM\ChannelRepository;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\Taxon;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class MenuExampleFactoryTest extends TestCase
{
    /** @var MockObject|ChannelRepositoryInterface */
    protected $channelRepositoryMock;

    /** @var MockObject|ProductRepositoryInterface */
    protected $productRepositoryMock;

    /** @var MockObject|TaxonRepositoryInterface */
    protected $taxonRepositoryMock;

    /** @var MenuExampleFactory */
    protected $menuExampleFactory;

    public function createMenuSuccessDataProvider(): array
    {
        $data = [];

        //create a simple menu (without translation channel or items
        $data[] = [
            'create_parameter' => [
                'code' => 'default_menu',
                'visibility' => 'public',
                'enabled' => true,
                'translations' => [],
                'channels' => [],
                'items' => [],
            ],

            'expected_result' => [
                'code' => 'default_menu',
                'visibility' => VisibilityTraitInterface::PUBLIC_VISIBILITY,
                'enabled' => true,
                //Just asset size for collection
                'translations_collection_size' => 0,
                'channel_collection_size' => 0,
                'items_collection_size' => 0,
            ],
        ];

        //with other states ans visibility
        $data[] = [
            'create_parameter' => [
                'code' => 'default_menu',
                'visibility' => 'private',
                'enabled' => false,
                'translations' => [],
                'channels' => [],
                'items' => [],
            ],

            'expected_result' => [
                'code' => 'default_menu',
                'visibility' => VisibilityTraitInterface::PRIVATE_VISIBILITY,
                'enabled' => false,
                //Just asset size for collection
                'translations_collection_size' => 0,
                'channel_collection_size' => 0,
                'items_collection_size' => 0,
            ],
        ];

        //with 1 translation channel and item
        $data[] = [
            'create_parameter' => [
                'code' => 'default_menu',
                'visibility' => 'public',
                'enabled' => true,
                'translations' => [
                    'en_US' => [
                        'title' => 'default title',
                    ],
                ],
                'channels' => ['DEFAULT_STORE'],

                'items' => [[
                    'target' => '_blank',
                    'translations' => [],
                    'link' => [
                        'custom_link' => [
                            'en_US' => '/#',
                        ],
                    ],
                ]],
            ],

            'expected_result' => [
                'code' => 'default_menu',
                'visibility' => VisibilityTraitInterface::PUBLIC_VISIBILITY,
                'enabled' => true,
                //Just asset size for collection
                'translations_collection_size' => 1,
                'channel_collection_size' => 1,
                'items_collection_size' => 1,
            ],
        ];

        //with more than 1 translations channels and items
        $data[] = [
            'create_parameter' => [
                'code' => 'default_menu',
                'visibility' => 'public',
                'enabled' => true,
                'translations' => [
                    'en_US' => [
                        'title' => 'default title',
                    ],
                    'fr_FR' => [
                        'title' => 'titre par défaut',
                    ],
                ],
                'channels' => ['DEFAULT_STORE', 'SECOND_STORE'],

                'items' => [
                    [
                        'target' => '_blank',
                        'translations' => [],
                        'link' => [
                            'custom_link' => [
                                'en_US' => '/#',
                            ],
                        ],
                    ],
                    [
                        'target' => '_self',
                        'translations' => [],
                        'link' => [
                            'custom_link' => [
                                'en_US' => '/other/link',
                            ],
                        ],
                    ],
                ],
            ],

            'expected_result' => [
                'code' => 'default_menu',
                'visibility' => VisibilityTraitInterface::PUBLIC_VISIBILITY,
                'enabled' => true,
                //Just asset size for collection
                'translations_collection_size' => 2,
                'channel_collection_size' => 2,
                'items_collection_size' => 2,
            ],
        ];

        return $data;
    }

    public function createItemSuccessDataProvider(): array
    {
        $data = [];

        //test with custom link
        $data[] = [
            'item_options' => [
                'target' => '_self',
                'css_classes' => 'link',
                'priority' => null,
                'translations' => [
                    'en_US' => [
                        'title' => 'default title',
                        'description' => 'default description',
                    ],
                ],
                'link' => [
                    'custom_link' => [
                        'en_US' => '/custom/link',
                    ],
                ],
            ],
            'expected_result' => [
                'target' => MenuItem::TARGET_SELF,
                'css_classes' => 'link',
                'priority' => null,
                'translations' => [
                    'en_US' => [
                        'title' => 'default title',
                        'description' => 'default description',
                    ],
                ],
                'link_type' => 'translations',
                'link_translation' => [
                    'en_US' => '/custom/link',
                ],
            ],
        ];

        //test with custom link in multiple locale
        $data[] = [
            'item_options' => [
                'target' => '_self',
                'css_classes' => 'link',
                'priority' => null,
                'translations' => [
                    'en_US' => [
                        'title' => 'default title',
                        'description' => 'default description',
                    ],
                ],
                'link' => [
                    'custom_link' => [
                        'en_US' => '/custom/link',
                        'fr_FR' => '/lien/personalise',
                    ],
                ],
            ],
            'expected_result' => [
                'target' => MenuItem::TARGET_SELF,
                'css_classes' => 'link',
                'priority' => null,
                'translations' => [
                    'en_US' => [
                        'title' => 'default title',
                        'description' => 'default description',
                    ],
                ],
                'link_type' => 'translations',
                'link_translation' => [
                    'en_US' => '/custom/link',
                    'fr_FR' => '/lien/personalise',
                ],
            ],
        ];

        //test with product link and other translation
        $data[] = [
            'item_options' => [
                'target' => '_blank',
                'css_classes' => null,
                'priority' => 10,
                'translations' => [
                    'en_US' => [
                        'title' => 'default title',
                        'description' => null,
                    ],
                    'fr_FR' => [
                        'title' => 'Titre par défaut',
                        'description' => 'Description par défaut',
                    ],
                ],
                'link' => [
                    'product_code' => 'DEFAULT_PRODUCT',
                ],
            ],
            'expected_result' => [
                'target' => MenuItem::TARGET_BLANK,
                'css_classes' => null,
                'priority' => 10,
                'translations' => [
                    'en_US' => [
                        'title' => 'default title',
                        'description' => null,
                    ],
                    'fr_FR' => [
                        'title' => 'Titre par défaut',
                        'description' => 'Description par défaut',
                    ],
                ],
                'link_type' => 'product',
            ],
        ];

        //test with taxon link
        $data[] = [
            'item_options' => [
                'target' => '_blank',
                'css_classes' => null,
                'priority' => 10,
                'translations' => [
                    'en_US' => [
                        'title' => 'default title',
                        'description' => 'default description',
                    ],
                ],
                'link' => [
                    'taxon_code' => 'DEFAULT_TAXON',
                ],
            ],
            'expected_result' => [
                'target' => MenuItem::TARGET_BLANK,
                'css_classes' => null,
                'priority' => 10,
                'translations' => [
                    'en_US' => [
                        'title' => 'default title',
                        'description' => 'default description',
                    ],
                ],
                'link_type' => 'taxon',
            ],
        ];

        return $data;
    }

    public function createItemThrowErrorDataProvider(): array
    {
        $data = [];

        //throw error if link is not defined
        $data[] = [
            'item_options' => [
                'target' => '_self',
                'css_classes' => 'link',
                'priority' => null,
                'translations' => [],
                'link' => [],
            ],
            'expected_error' => [
                'type' => InvalidConfigurationException::class,
                'message' => 'The link should be defined. Please, set custom_link, product_code or taxon_code node',
            ],
        ];

        //throw error if product code is invalid
        $data[] = [
            'item_options' => [
                'target' => '_self',
                'css_classes' => 'link',
                'priority' => null,
                'translations' => [],
                'link' => [
                    'product_code' => 'INVALID_PRODUCT',
                ],
            ],
            'expected_error' => [
                'type' => \InvalidArgumentException::class,
                'message' => 'Product with code INVALID_PRODUCT not found',
            ],
        ];

        //throw error if product code is invalid
        $data[] = [
            'item_options' => [
                'target' => '_self',
                'css_classes' => 'link',
                'priority' => null,
                'translations' => [],
                'link' => [
                    'taxon_code' => 'INVALID_TAXON',
                ],
            ],
            'expected_error' => [
                'type' => \InvalidArgumentException::class,
                'message' => 'Taxon with code INVALID_TAXON not found',
            ],
        ];

        return $data;
    }

    protected function setUp(): void
    {
        $menuFactory = new MenuFactory(new Factory(Menu::class));
        $menuItemFactory = new MenuItemFactory(new Factory(MenuItem::class));
        $menuLinkFactory = new MenuLinkFactory(new Factory(MenuLink::class));
        $this->getMocks();

        $this->menuExampleFactory = new MenuExampleFactory(
            $menuFactory,
            $menuItemFactory,
            $menuLinkFactory,
            $this->channelRepositoryMock,
            $this->productRepositoryMock,
            $this->taxonRepositoryMock
        );
    }

    /**
     * @test
     * @dataProvider createMenuSuccessDataProvider
     */
    public function it_create_menu_success(array $createOptions, array $expectedResult): void
    {
        $this->channelRepositoryMock
            ->method('findOneBy')
            ->willReturn(new Channel());

        $menu = $this->menuExampleFactory->create($createOptions);

        $this->assertInstanceOf(MenuInterface::class, $menu);
        $this->assertSame(
            $expectedResult['code'],
            $menu->getCode()
        );

        $this->assertSame(
            $expectedResult['visibility'],
            $menu->getVisibility(),
            'Expected visibility is different to actual visibility'
        );

        $this->assertSame(
            $expectedResult['enabled'],
            $menu->isEnabled(),
            'The menu has not the expected state'
        );

        $this->assertEquals(
            $expectedResult['translations_collection_size'],
            $menu->getTranslations()->count(),
            'Menu\'d translations has not expected size'
        );

        $this->assertEquals(
            $expectedResult['channel_collection_size'],
            $menu->getChannels()->count(),
            'Menu\'s channels has not expected size'
        );

        $this->assertEquals(
            $expectedResult['items_collection_size'],
            $menu->getMenuItems()->count(),
            'Menu\'s items collection has not expected size'
        );
    }

    /**
     * Function to test protected sub-method : createItem
     *
     * @test
     * @dataProvider createItemSuccessDataProvider
     */
    public function it_create_items_success(array $itemOptions, array $expectedResult): void
    {
        //prepare mock
        $this->productRepositoryMock
            ->method('findOneByCode')
            ->willReturn(new Product());
        $this->taxonRepositoryMock
            ->method('findOneBy')
            ->willReturn(new Taxon());

        //invoke protected method
        $item = $this->invokeCreateItem($itemOptions);

        //Do assert
        $this->assertInstanceOf(
            MenuItemInterface::class,
            $item
        );

        $this->assertSame(
            $expectedResult['target'],
            $item->getTarget(),
            'This items has not the expected target'
        );

        $this->assertSame(
            $expectedResult['css_classes'],
            $item->getCssClasses(),
            'This items has not expected CSS classes'
        );

        $this->assertEquals(
            $expectedResult['priority'],
            $item->getPriority(),
            'This items has not expected priority'
        );

        foreach ($expectedResult['translations'] as $locale => $translation) {
            $item->setCurrentLocale($locale);
            $this->assertSame($translation['title'], $item->getTitle());
            $this->assertSame($translation['description'], $item->getDescription());
        }

        $this->assertSame(
            $expectedResult['link_type'],
            $item->getLink()->getType(),
            sprintf('This item should have "%s" type link. Actual : "%s"', $expectedResult['link_type'], $item->getLink()->getType())
        );

        //assert translation for custom link
        if ($expectedResult['link_type'] === MenuLinkInterface::CUSTOM_LINK_PROPERTY) {
            /** @var MenuLinkInterface $link */
            $link = $item->getLink();
            foreach ($expectedResult['link_translation'] as $locale => $path) {
                $link->setCurrentLocale($locale);
                $this->assertSame(
                    $path,
                    $link->getCustomLink()
                );
            }
        }
    }

    /**
     * @test
     * @dataProvider createItemThrowErrorDataProvider
     */
    public function create_item_throw_error(array $itemOptions, array $expectedError): void
    {
        //prepare mock
        $this->productRepositoryMock
            ->method('findOneByCode')
            ->willReturn(null);
        $this->taxonRepositoryMock
            ->method('findOneBy')
            ->willReturn(null);

        //expect methods
        $this->expectException($expectedError['type']);
        $this->expectExceptionMessage($expectedError['message']);

        //invoke protected method
        $this->invokeCreateItem($itemOptions);
    }

    protected function getMocks()
    {
        $this->channelRepositoryMock = $this->getMockBuilder(ChannelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productRepositoryMock = $this->getMockBuilder(ProductRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->taxonRepositoryMock = $this->getMockBuilder(TaxonRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function invokeCreateItem(array $createItemArguments): MenuItemInterface
    {
        $class = new \ReflectionClass($this->menuExampleFactory);
        $createItemMethod = $class->getMethod('createItem');
        $createItemMethod->setAccessible(true);

        return $createItemMethod->invoke($this->menuExampleFactory, $createItemArguments);
    }
}
