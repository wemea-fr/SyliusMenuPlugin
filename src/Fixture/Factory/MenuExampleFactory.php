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
        /** @psalm-suppress UnusedClosureParam */
        $resolver
            ->setDefined('code')
            ->setAllowedTypes('code', 'string')

            ->setDefault('enabled', true)
            ->setAllowedTypes('enabled', 'boolean')

            ->setDefault('visibility', 'public')
            ->setAllowedTypes('visibility', 'string')
            ->setNormalizer('visibility', function (Options $options, string $value): int {
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
                /** @psalm-suppress MixedArgument */
                return array_map([$this, 'createItem'], $value);
            })
        ;
    }

    public function create(array $options = []): MenuInterface
    {
        $options = $this->optionsResolver->resolve($options);
        /** @var MenuInterface $menu */
        $menu = $this->menuFactory->createNew();

        /** @var string $code */
        $code = $options['code'];
        $menu->setCode($code);
        /** @var bool $enabled */
        $enabled = $options['enabled'];
        $menu->setEnabled($enabled);
        /** @var int $visibility */
        $visibility = $options['visibility'];
        $menu->setVisibility($visibility);

        /**
         * @var string $locale
         * @var array $value
         */
        foreach ($options['translations'] as $locale => $value) {
            $menu->setCurrentLocale($locale);
            /** @var ?string $title */
            $title = $value['title'];
            $menu->setTitle($title);
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

        $menuItem->setTarget((string) $itemParameters['target']);
        /** @var ?int $priority */
        $priority = $itemParameters['priority'] ?? null;
        $menuItem->setPriority($priority);
        /** @var ?string $cssClasses */
        $cssClasses = $itemParameters['css_classes'] ?? null;
        $menuItem->setCssClasses($cssClasses);

        /**
         * @var  string $locale
         * @var  string[] $translation */
        foreach ($itemParameters['translations'] as $locale => $translation) {
            $menuItem->setCurrentLocale($locale);
            $menuItem->setTitle($translation['title']);
            $menuItem->setDescription($translation['description']);
        }

        //Create Link
        /** @var MenuLinkInterface $link */
        $link = $this->menuLinkFactory->createForMenuItem($menuItem);
        /** @var ?array $itemParamsLink */
        $itemParamsLink = array_key_exists('link', $itemParameters) ? $itemParameters['link'] : null;
        /** @var ?string $productCode */
        $productCode = is_array($itemParamsLink) && array_key_exists('product_code', $itemParamsLink) ? $itemParamsLink['product_code'] : null;
        /** @var ?string $taxonCode */
        $taxonCode = is_array($itemParamsLink) && array_key_exists('taxon_code', $itemParamsLink) ? $itemParamsLink['taxon_code'] : null;
        if (is_array($itemParamsLink) && array_key_exists('custom_link', $itemParamsLink) && is_array($itemParamsLink['custom_link']) && count($itemParamsLink['custom_link']) > 0) {
            $link->setLinkResource(MenuLinkInterface::CUSTOM_LINK_PROPERTY, new ArrayCollection());
            /**
             * @var string $locale
             * @var string  $path */
            foreach ($itemParamsLink['custom_link'] as $locale => $path) {
                $link->setCurrentLocale($locale);
                $link->setCustomLink($path);
            }
        } elseif (null !== $productCode) {
            $product = $this->productRepository->findOneByCode($productCode);
            Assert::notNull(
                $product,
                sprintf('Product with code %s not found', $productCode),
            );
            $link->setLinkResource('product', $product);
        } elseif (null !== $taxonCode) {
            $taxon = $this->taxonRepository->findOneBy(['code' => $taxonCode]);
            Assert::notNull(
                $taxon,
                sprintf('Taxon with code %s not found', $taxonCode),
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
