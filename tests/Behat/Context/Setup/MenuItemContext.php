<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Sylius\Component\Locale\Converter\LocaleConverterInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkInterface as MenuLinkInterfaceEntity;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkTranslationInterface;
use Wemea\SyliusMenuPlugin\Factory\MenuItemFactoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuInterface;
use Wemea\SyliusMenuPlugin\Model\MenuLinkInterface;

class MenuItemContext implements Context
{
    public const DEFAULT_LINK = 'https://www.wemea.fr';

    public const DEFAULT_LOCALE = 'en_US';

    /** @var MenuItemFactoryInterface */
    protected $menuItemFactory;

    /** @var RepositoryInterface */
    protected $menuItemRepository;

    /** @var SharedStorageInterface */
    protected $sharedStorage;

    /** @var FactoryInterface */
    protected $menuLinkFactory;

    /** @var FactoryInterface */
    protected $menuImageFactory;

    /** @var EntityManager */
    protected $_em;

    /** @var ImageUploaderInterface */
    protected $imageUploader;

    /** @var array|\ArrayAccess */
    protected $minkParameters;

    /** @var LocaleConverterInterface */
    protected $localeConverter;

    /** @var FactoryInterface */
    protected $customLinkTranslationFactory;

    public function __construct(
        MenuItemFactoryInterface $menuItemFactory,
        RepositoryInterface $menuItemRepository,
        SharedStorageInterface $sharedStorage,
        FactoryInterface $menuLinkFactory,
        FactoryInterface $menuImageFactory,
        Factoryinterface $customLinkTranslationFactory,
        EntityManager $manager,
        ImageUploaderInterface $imageUploader,
        LocaleConverterInterface $localeConverter,
        $minkParameters,
    ) {
        if (!is_array($minkParameters) && !$minkParameters instanceof \ArrayAccess) {
            throw new \InvalidArgumentException(sprintf(
                '"$minkParameters" passed to "%s" has to be an array or implement "%s".',
                self::class,
                \ArrayAccess::class,
            ));
        }

        $this->menuItemFactory = $menuItemFactory;
        $this->menuItemRepository = $menuItemRepository;
        $this->sharedStorage = $sharedStorage;
        $this->menuLinkFactory = $menuLinkFactory;
        $this->menuImageFactory = $menuImageFactory;
        $this->customLinkTranslationFactory = $customLinkTranslationFactory;
        $this->_em = $manager;
        $this->imageUploader = $imageUploader;
        $this->localeConverter = $localeConverter;
        $this->minkParameters = $minkParameters;
    }

    /**
     * @Given /^(this menu) has one item$/
     * @Given /^(this menu) has one item with title "([^"]*)"$/
     * @Given /^(this menu) has another item with title "([^"]*)"$/
     * @Given /^(this menu) has one item with title "([^"]*)" in "([^"]*)"$/
     */
    public function thisMenuHasOneItemWithTitle(MenuInterface $menu, string $title = 'default title', string $locale = 'en_US')
    {
        $item = $this->createItem($menu);
        $item->setCurrentLocale($locale);
        $item->setTitle($title);
        $this->saveNewItem($item);
    }

    /**
     * @Given /^(this menu item) redirect on "([^"]*)"$/
     * @Given /^(this menu item) redirect on "([^"]*)" in "([^"]*)"$/
     */
    public function thisMenuItemPointOn(MenuItemInterface $menuItem, string $linkTarget, ?string $locale = null)
    {
        /** @var MenuLinkInterfaceEntity $link */
        $link = $menuItem->getLink();

        $localeCode = $locale ? $this->localeConverter->convertNameToCode($locale) : self::DEFAULT_LOCALE;

        //init translation if not set
        if ($link->getType() !== MenuLinkInterfaceEntity::CUSTOM_LINK_PROPERTY) {
            $customLink = $this->createCustomLinkTranslation($linkTarget, $localeCode, $link);
            $link->setLinkResource('translations', new ArrayCollection([$customLink]));
        } else {
            //else add custom link with default behaviour
            $link->setCurrentLocale($localeCode);
            $link->setCustomLink($linkTarget);
        }

        $menuItem->setLink($link);
        $this->persistItem($menuItem);
    }

    /**
     * @Given /^(this menu item) redirect on the (product "[^"]+")$/
     */
    public function thisMenuRedirectOnProduct(MenuItemInterface $menuItem, ProductInterface $product)
    {
        $link = $menuItem->getLink();
        $link->setLinkResource('product', $product);
        $menuItem->setLink($link);
        $this->persistItem($menuItem);
    }

    /**
     * @Given /^(this menu item) redirect on the (taxon "[^"]+")$/
     */
    public function thisMenuRedirectOnTaxon(MenuItemInterface $menuItem, TaxonInterface $taxon)
    {
        $link = $menuItem->getLink();
        $link->setLinkResource('taxon', $taxon);
        $menuItem->setLink($link);
        $this->persistItem($menuItem);
    }

    /**
     * @Given /^(this menu item) has "([^"]*)" as icon$/
     * @Given /^the ("[^"]+" item) has "([^"]*)" as icon$/
     */
    public function thisItemHasAsIcon(MenuItemInterface $menuItem, string $imageName)
    {
        $this->createMenuItemImage($menuItem, $imageName);
    }

    protected function createItem(MenuInterface $menu, string $target = '_self', ?MenuLinkInterface $link = null): MenuItemInterface
    {
        /** @var MenuItemInterface $item */
        $item = $this->menuItemFactory->createForMenu($menu);
        $item->setTarget($target);
        if (null === $link) {
            $link = $this->createDefaultLink();
        }
        $link->setOwner($item); //TODO: prefer factory with createForItem() ?
        $item->setLink($link);

        return $item;
    }

    /**
     * @Given /^the ("[^"]+" item) has (_blank|_self) as target$/
     * @Given /^(this menu item) has (_blank|_self) as target$/
     */
    public function itemHasAsTarget(MenuItemInterface $menuItem, $target)
    {
        $menuItem->setTarget($target);
        $this->persistItem($menuItem);
    }

    /**
     * @Given /^the ("([^"]+)" item) has not priority$/
     */
    public function theItemHasNotPriority(MenuItemInterface $menuItem)
    {
        $menuItem->setPriority(null);
        $this->persistItem($menuItem);
    }

    /**
     * @Given /^the ("[^"]+" item) has (-?\d+) as priority$/
     */
    public function theItemHasAsPriority(MenuItemInterface $menuItem, int $priority)
    {
        $menuItem->setPriority($priority);
        $this->persistItem($menuItem);
    }

    protected function createDefaultLink(): MenuLinkInterface
    {
        /** @var MenuLinkInterfaceEntity $link */
        $link = $this->menuLinkFactory->createNew();
        $link->setLinkResource('translations', new ArrayCollection());

        $link->setCurrentLocale(self::DEFAULT_LOCALE);
        $link->setCustomLink(self::DEFAULT_LINK);

        return $link;
    }

    protected function saveNewItem(MenuItemInterface $item): void
    {
        $this->menuItemRepository->add($item);
        $this->sharedStorage->set('menu_item', $item);
    }

    protected function persistItem(MenuItemInterface $item): void
    {
        $this->_em->persist($item);
        $this->_em->flush();
        $this->sharedStorage->set('menu_item', $item);
    }

    protected function createMenuItemImage(MenuItemInterface $menuItem, string $imageName): void
    {
        $filesPath = $this->getParameter('files_path');

        /** @var ImageInterface $itemImage */
        $itemImage = $this->menuImageFactory->createNew();
        $itemImage->setFile(new UploadedFile($filesPath . $imageName, basename($imageName)));

        $this->imageUploader->upload($itemImage);

        $menuItem->setImage($itemImage);

        $this->sharedStorage->set('last_menu_item_image_path', $itemImage->getPath());

        $this->persistItem($menuItem);
    }

    /**
     * @return NodeElement|string|null
     */
    protected function getParameter(string $name)
    {
        return $this->minkParameters[$name] ?? null;
    }

    protected function createCustomLinkTranslation(string $path, string $localeCode, MenuLinkInterfaceEntity $link): MenuLinkTranslationInterface
    {
        /** @var MenuLinkTranslationInterface $customLink */
        $customLink = $this->customLinkTranslationFactory->createNew();
        $customLink->setLocale($localeCode);
        $customLink->setTranslatable($link);
        $customLink->setCustomLink($path);

        return $customLink;
    }
}
