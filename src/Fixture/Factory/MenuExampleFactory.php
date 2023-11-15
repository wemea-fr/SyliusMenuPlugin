<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Fixture\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Wemea\SyliusMenuPlugin\Entity\MenuInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkInterface;
use Wemea\SyliusMenuPlugin\Factory\MenuFactoryInterface;
use Wemea\SyliusMenuPlugin\Factory\MenuItemFactoryInterface;
use Wemea\SyliusMenuPlugin\Factory\MenuLinkFactoryInterface;
use Wemea\SyliusMenuPlugin\Model\VisibilityTraitInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

class MenuExampleFactory extends AbstractExampleFactory
{
    //TODO : add image support for

    /** @var MenuFactoryInterface */
    protected $menuFactory;

    /** @var OptionsResolver */
    protected $optionsResolver;

    /** @var ChannelRepositoryInterface */
    protected $channelRepository;

    /** @var MenuItemFactoryInterface */
    protected $menuItemFactory;

    /** @var MenuLinkFactoryInterface */
    protected $menuLinkFactory;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    /** @var TaxonRepositoryInterface */
    protected $taxonRepository;

    public function __construct(
        MenuFactoryInterface $menuFactory,
        MenuItemFactoryInterface $menuItemFactory,
        MenuLinkFactoryInterface $menuLinkFactory,
        ChannelRepositoryInterface $channelRepository,
        ProductRepositoryInterface $productRepository,
        TaxonRepositoryInterface $taxonRepository,
    ) {
        $this->menuFactory = $menuFactory;
        $this->menuItemFactory = $menuItemFactory;
        $this->menuLinkFactory = $menuLinkFactory;
        $this->channelRepository = $channelRepository;
        $this->productRepository = $productRepository;
        $this->taxonRepository = $taxonRepository;

        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined('code')
            ->setAllowedTypes('code', 'string')

            ->setDefault('enabled', true)
            ->setAllowedTypes('enabled', 'boolean')

            ->setDefault('visibility', 'public')
            ->setAllowedTypes('visibility', 'string')
            ->setNormalizer('visibility', function (Options $options, $value): int {
                /** @var int $visibilityValue */
                $visibilityValue = array_search($value, VisibilityTraitInterface::VISIBILITY_CODE, true);

                return  $visibilityValue;
            })

            ->setDefined('translations')
            ->setAllowedTypes('translations', 'array')

            //FIXME : default value not used
            ->setDefault('channels', LazyOption::all($this->channelRepository))
            ->setAllowedTypes('channels', 'array')
            ->setNormalizer('channels', LazyOption::findBy($this->channelRepository, 'code'))

            ->setDefined('items')
            ->setNormalizer('items', function (Options $options, array $value): array {
                return array_map([$this, 'createItem'], $value);
            })
        ;
    }

    public function create(array $options = []): MenuInterface
    {
        $options = $this->optionsResolver->resolve($options);
        /** @var MenuInterface $menu */
        $menu = $this->menuFactory->createNew();

        $menu->setCode($options['code']);
        $menu->setEnabled($options['enabled']);
        $menu->setVisibility($options['visibility']);

        foreach ($options['translations'] as $locale => $value) {
            $menu->setCurrentLocale($locale);
            $menu->setTitle($value['title']);
        }

        /** @var ChannelInterface $channel */
        foreach ($options['channels'] as $channel) {
            $menu->addChannel($channel);
        }

        /** @var MenuItemInterface $item */
        foreach ($options['items'] as $item) {
            $item->setMenu($menu);
            $menu->addMenuItem($item);
        }

        return $menu;
    }

    protected function createItem(array $itemParameters): MenuItemInterface
    {
        /** @var MenuItemInterface $menuItem */
        $menuItem = $this->menuItemFactory->createNew();

        $menuItem->setTarget($itemParameters['target']);
        $menuItem->setPriority($itemParameters['priority'] ?? null);
        $menuItem->setCssClasses($itemParameters['css_classes'] ?? null);

        /**
         * @var  string $locale
         * @var  array|string[] $translation */
        foreach ($itemParameters['translations'] as $locale => $translation) {
            $menuItem->setCurrentLocale($locale);
            $menuItem->setTitle($translation['title']);
            $menuItem->setDescription($translation['description']);
        }

        //Create Link
        /** @var MenuLinkInterface $link */
        $link = $this->menuLinkFactory->createForMenuItem($menuItem);
        if (array_key_exists('custom_link', $itemParameters['link']) && count($itemParameters['link']['custom_link']) > 0) {
            $link->setLinkResource(MenuLinkInterface::CUSTOM_LINK_PROPERTY, new ArrayCollection());
            /**
             * @var string $locale
             * @var string  $path */
            foreach ($itemParameters['link']['custom_link'] as $locale => $path) {
                $link->setCurrentLocale($locale);
                $link->setCustomLink($path);
            }
        } elseif (null !== ($itemParameters['link']['product_code'] ?? null)) {
            $product = $this->productRepository->findOneByCode($itemParameters['link']['product_code']);
            Assert::notNull(
                $product,
                sprintf('Product with code %s not found', $itemParameters['link']['product_code']),
            );
            $link->setLinkResource('product', $product);
        } elseif (null !== ($itemParameters['link']['taxon_code'] ?? null)) {
            $taxon = $this->taxonRepository->findOneBy(['code' => $itemParameters['link']['taxon_code']]);
            Assert::notNull(
                $taxon,
                sprintf('Taxon with code %s not found', $itemParameters['link']['taxon_code']),
            );
            $link->setLinkResource('taxon', $taxon);
        } else {
            //If custom link translation is empty and product code is null and taxon code is null => throw Invalidation configuration error
            throw new InvalidConfigurationException('The link should be defined. Please, set custom_link, product_code or taxon_code node');
        }

        $menuItem->setLink($link);

        return $menuItem;
    }
}
