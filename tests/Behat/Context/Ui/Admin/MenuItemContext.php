<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Locale\Converter\LocaleConverterInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Tests\Wemea\SyliusMenuPlugin\Behat\Helper\ImageMatcherHelperInterface;
use Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\MenuItem\CreatePageInterface;
use Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\MenuItem\IndexPageInterface;
use Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\MenuItem\UpdatePageInterface;
use Webmozart\Assert\Assert;

class MenuItemContext implements Context
{
    protected const DEFAULT_LOCALE_CODE = 'en_US';

    /** @var IndexPageInterface */
    protected $indexPage;

    /** @var CreatePageInterface */
    protected $createPage;

    /** @var SharedStorageInterface */
    protected $sharedStorage;

    /** @var LocaleConverterInterface */
    protected $localeConverter;

    /** @var CurrentPageResolverInterface */
    protected $pageResolver;

    /** @var UpdatePageInterface */
    protected $updatePage;

    /** @var ImageMatcherHelperInterface */
    protected $imageMatcher;

    /** @var EntityManagerInterface */
    protected $_em;

    /** @var string */
    protected $menuItemClass;

    public function __construct(
        IndexPageInterface $indexPage,
        CreatePageInterface $createPage,
        UpdatePageInterface $updatePage,
        SharedStorageInterface $sharedStorage,
        LocaleConverterInterface $localeConverter,
        CurrentPageResolverInterface $currentPageResolver,
        ImageMatcherHelperInterface $imageMatcherHelper,
        EntityManagerInterface $manager,
        string $menuItemClass,
    ) {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->sharedStorage = $sharedStorage;
        $this->localeConverter = $localeConverter;
        $this->pageResolver = $currentPageResolver;
        $this->imageMatcher = $imageMatcherHelper;
        $this->_em = $manager;
        $this->menuItemClass = $menuItemClass;
    }

    /**
     * @Given /^I try to access at the items page of (this menu)$/
     */
    public function iTryToAccessAtTheItemsPageOfThisMenu(MenuInterface $menu)
    {
        $this->indexPage->tryToOpen(['menuId' => $menu->getId()]);
    }

    /**
     * @Then /^I should be on the items page of (this menu)$/
     */
    public function iShouldBeOnTheItemsPageOfThisMenu(MenuInterface $menu)
    {
        $this->indexPage->verify(['menuId' => $menu->getId()]);
    }

    /**
     * @Given /^I want to manage items of (this menu)$/
     */
    public function iWantToManageItemsOfThisMenu(MenuInterface $menu)
    {
        $this->indexPage->open(['menuId' => $menu->getId()]);
    }

    /**
     * @Then /^I should see (1) item$/
     * @Then /^I should see (\d+) items$/
     */
    public function iShouldSeeItem(int $expectedItemsNumber)
    {
        $numberItem = $this->indexPage->countItems();
        Assert::eq(
            $numberItem,
            $expectedItemsNumber,
            sprintf('There is %d menu\'s item%s. Expected : %d ', $numberItem, $numberItem > 1 ? 's' : '', $expectedItemsNumber),
        );
    }

    /**
     * FIXME: use page resolver or other context to use same sentence as MenuContext
     *
     * @Given /^I should see "([^"]*)", "([^"]*)" and "([^"]*)" columns on the grid$/
     * @Given /^I should see "([^"]*)" column on the grid$/
     * @Given /^I should see "([^"]*)" column too on the grid$/
     */
    public function iShouldSeeAndColumns(...$expectedColumnsName)
    {
        $columnsNames = $this->indexPage->getColumnsNames();

        foreach ($expectedColumnsName as $expectedColumn) {
            Assert::inArray(
                $expectedColumn,
                $columnsNames,
                sprintf('the column "%s" not found. Current columns : %s', $expectedColumn, implode(', ', $columnsNames)),
            );
        }
    }

    /**
     * @Then /^I should see code of (this menu) on breadcrumb$/
     */
    public function iShouldSeeCodeOfThisMenuOnBreadcrumb(MenuInterface $menu)
    {
        Assert::true(
            $this->indexPage->isElementOnBreadcrumb($menu->getCode()),
            sprintf('item "%s" not found', $menu->getCode()),
        );
    }

    /**
     * @When /^I click parent menu link on the breadcrumb$/
     */
    public function iClickOnThisBreadcrumbItem()
    {
        $this->indexPage->clickOnParentMenuLink();
    }

    /**
     * @Then /^I should see create button$/
     */
    public function iShouldSeeCreateButton()
    {
        Assert::true(
            $this->indexPage->isCreateButton(),
            'There is not create button',
        );
    }

    /**
     * @When /^I click on create button$/
     */
    public function iClickOnCreateButton()
    {
        $this->indexPage->clickOnCreateButton();
    }

    /**
     * @Then /^I should be on create page$/
     */
    public function iShouldBeOnCreatePage()
    {
        /** @var MenuInterface $menu */
        $menu = $this->sharedStorage->get('menu');
        Assert::notNull(
            $menu,
        );

        $this->createPage->verify(['menuId' => $menu->getId()]);
    }

    /**
     * @Given /^I want to create a new item for (this menu)$/
     */
    public function iWantToCreateANewItemForThisMenu(MenuInterface $menu)
    {
        $this->createPage->open(['menuId' => $menu->getId()]);
    }

    /**
     * @Given /^I want to edit (this menu item)$/
     */
    public function iWantToEditThisMenuItem(MenuItemInterface $menuItem)
    {
        /** @var MenuInterface $menu */
        $menu = $menuItem->getMenu();
        $this->updatePage->open([
            'menuId' => $menu->getId(),
            'id' => $menuItem->getId(),
        ]);
    }

    /**
     * @Then /^I should see "([^"]*)" field$/
     * @Then /^I should see "([^"]*)" and "([^"]*)" fields$/
     * @Then /^I should see "([^"]*)", "([^"]*)" and "([^"]*)" fields$/
     */
    public function iShouldSeeField(...$expectedFields)
    {
        foreach ($expectedFields as $field) {
            $fieldCode = $this->resolveFieldFromString($field);
            Assert::true(
                $this->resolveCurrentPage()->isField($fieldCode),
                sprintf('The field "%s" not found"', $field),
            );
        }
    }

    /**
     * @Then /^I should see "([^"]*)" link field$/
     * @Then /^I should see "([^"]*)" and "([^"]*)" link fields$/
     * @Then /^I should see "([^"]*)", "([^"]*)" and "([^"]*)" link fields$/
     */
    public function iShouldSeeLinkField(...$expectedFields)
    {
        foreach ($expectedFields as $field) {
            $fieldCode = $this->resolveFieldFromString($field);
            Assert::true(
                $this->resolveCurrentPage()->isLinkField($fieldCode),
                sprintf('The field "%s" not found"', $field),
            );
        }
    }

    /**
     * @Given /^I should see "([^"]*)" and "([^"]*)" fields in "([^"]*)"$/
     */
    public function iShouldSeeAndFieldsIn(string $field1, string $field2, string $locale)
    {
        $localeCode = $this->localeConverter->convertNameToCode($locale);
        Assert::true(
            $this->resolveCurrentPage()->isTranslatableField($field1, $localeCode),
            sprintf('The field "%s" not found"', $field1),
        );
        Assert::true(
            $this->resolveCurrentPage()->isTranslatableField($field2, $localeCode),
            sprintf('The field "%s" not found"', $field2),
        );
    }

    /**
     * @When I set :value as :property
     * @When I select :value as :property
     * @When I set :value for :property
     */
    public function iSetAsTarget(string $value, string $property)
    {
        $field = $this->resolveFieldFromString($property);
        $this->resolveCurrentPage()->setFieldValue($field, $value);
    }

    /**
     * @When I set :value for :property in :locale
     */
    public function iSetForIn(string $value, string $property, string $locale)
    {
        $localeCode = $this->localeConverter->convertNameToCode($locale);
        $field = $this->resolveFieldFromString($property);
        $this->resolveCurrentPage()->setTranslatableFieldValue($field, $value, $localeCode);
    }

    /**
     * @Given I select :value as link :property
     * Given I set :value for :property link
     */
    public function iSelectAsLinkType(string $value, string $property)
    {
        $field = $this->resolveFieldFromString($property);
        $this->resolveCurrentPage()->setLinkFieldValue($field, $value);
    }

    /**
     * @Given I set :value for custom link
     * @Given I set :value for custom link in :locale
     */
    public function iSetForCustomLinkIn(string $value, ?string $locale = null)
    {
        $localeCode = $locale ? $this->localeConverter->convertNameToCode($locale) : self::DEFAULT_LOCALE_CODE;
        $this->resolveCurrentPage()->setCustomLinkUrl($value, $localeCode);
    }

    /**
     * @Given I add it
     */
    public function iAddIt()
    {
        $this->createPage->create();
        /** @var array $menuItems */
        $menuItems = $this->_em->getRepository($this->menuItemClass)->findAll();
        if (!empty($menuItems)) {
            $this->sharedStorage->set('menu_item', end($menuItems));
        }
    }

    /**
     * @Given /^the item with title "([^"]*)" appear on the grid$/
     */
    public function theItemWithTitleAppearOnTheGrid(string $title)
    {
        Assert::true(
            $this->indexPage->isSingleResourceOnPage(['title' => $title]),
            sprintf('The link with title "%s" not found', $title),
        );
    }

    /**
     * @Given /^the item with title "([^"]*)" should not appear on the grid$/
     */
    public function theItemWithTitleNotAppearOnTheGrid(string $title)
    {
        Assert::false(
            $this->indexPage->isSingleResourceOnPage(['title' => $title]),
            sprintf('The link with title "%s" found on the grid. Expected not', $title),
        );
    }

    /**
     * @Given /^I set the (product "[^"]+") as link$/
     */
    public function iSetTheProductAsLink(ProductInterface $product)
    {
        $this->resolveCurrentPage()->setProductLink($product);
    }

    /**
     * @Given /^the item with title "([^"]*)" and type "([^"]*)" appear on the grid$/
     */
    public function theItemWithTitleAndTypeAppearOnTheGrid(string $title, string $type)
    {
        Assert::true(
            $this->indexPage->isSingleResourceOnPage(['title' => $title, 'link.getType' => $type]),
            sprintf('The link with title "%s" ans "%s" type not found', $title, $type),
        );
    }

    /**
     * @Given /^I set the (taxon "[^"]+") as link$/
     */
    public function iSetTheTaxonAsLink(TaxonInterface $taxon)
    {
        $this->resolveCurrentPage()->setTaxonLink($taxon);
    }

    /**
     * @Then /^the "([^"]*)" link field is visible$/
     */
    public function theCustomFieldIsVisible(string $field)
    {
        Assert::true(
            $this->resolveCurrentPage()->linkFieldIsVisible($field),
            sprintf('the field "%s" expected to be visible', $field),
        );
    }

    /**
     * @Given /^the "([^"]*)" link field is hidden$/
     * @Given /^the "([^"]*)" and "([^"]*)" link fields are hidden$/
     */
    public function theProductAndTaxonLinkFieldsAreHidden(...$links)
    {
        foreach ($links as $link) {
            Assert::false(
                $this->resolveCurrentPage()->linkFieldIsVisible($link),
                sprintf('the field "%s" expected to be hidden', $link),
            );
        }
    }

    /**
     * @Given I save my changes about this item
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @Then the link's type is :type
     */
    public function theLinkSTypeIs(string $type)
    {
        $currentValue = $this->updatePage->getLinkFieldValue('type');
        Assert::same(
            $currentValue,
            $type,
        );
    }

    /**
     * @Given the value of :field link field is :value
     */
    public function theValueOfCustomLinkFieldIs(string $field, string $value)
    {
        //to check null value, transform null string to empty string
        if ($value === 'null') {
            $value = '';
        }

        $currentValue = $this->updatePage->getLinkFieldValue($field);

        Assert::same(
            $currentValue,
            $value,
        );
    }

    /**
     * @Given the value of custom link field is :value in :locale
     */
    public function theValueOfCustomLinkFieldIsIn(string $value, string $locale)
    {
        $localeCode = $this->localeConverter->convertNameToCode($locale);
        //to check null value, transform null string to empty string
        if ($value === 'null') {
            $value = '';
        }

        $currentValue = $this->updatePage->getCustomLinkFieldValue($localeCode);

        Assert::same(
            $currentValue,
            $value,
        );
    }

    /**
     * @Then I should be notified the :field is required in :locale with message :expectedMessage
     */
    public function iShouldBeNotifiedTheTitleIsRequiredInWithMessage(string $field, string $locale, string $expectedMessage)
    {
        $localeCode = $this->localeConverter->convertNameToCode($locale);
        Assert::same(
            $this->resolveCurrentPage()->getErrorMessageOfTranslatableField($field, $localeCode),
            $expectedMessage,
        );
    }

    /**
     * @Then I should be notified the resource target of the link is required with message :expectedMessage
     */
    public function iShouldBeNotifiedTheResourceTargetOfTheLinkIsRequiredWithMessage(string $expectedMessage)
    {
        Assert::same(
            $this->resolveCurrentPage()->getTargetResourceLinkErrorMessage(),
            $expectedMessage,
        );
    }

    /**
     * @Given /^I attach "([^"]*)" image as icon$/
     */
    public function iAttachImageAsIcon(string $file)
    {
        $this->resolveCurrentPage()->attachImage($file);
    }

    /**
     * @Given /^"([^"]*)" is associated to (this menu item) as icon$/
     */
    public function thisItemHasAsImage(string $expectedImage, MenuItemInterface $item)
    {
        //refresh to have the correct image (current on DB)
        $this->_em->refresh($item);
        $savedImage = $item->getImage();

        Assert::notNull(
            $savedImage,
            'This items has not icon',
        );

        Assert::notNull(
            $savedImage->getPath(),
            'The image has not path',
        );

        $this->imageMatcher->imageMatchToSyliusFixture($expectedImage, $savedImage->getPath());
    }

    /**
     * @Given /^last menu item icon is removed$/
     */
    public function lastMenuItemIconIsRemoved()
    {
        /** @var string $lastImagePath */
        $lastImagePath = $this->sharedStorage->get('last_menu_item_image_path');
        $this->imageMatcher->imageIsRemoved($lastImagePath);

        //set the new 'last image'
        /** @var MenuItemInterface $menuItem */
        $menuItem = $this->sharedStorage->get('menu_item');
        $this->_em->refresh($menuItem);
        $this->sharedStorage->set('last_menu_item_image_path', $menuItem->getImage() !== null ? $menuItem->getImage()->getPath() : null);
    }

    /**
     * @Given /^(this menu item) has not image as icon$/
     */
    public function thisMenuItemHasNotImageAsIcon(MenuItemInterface $menuItem)
    {
        $this->_em->refresh($menuItem);
        Assert::null(
            $menuItem->getImage(),
            'This item has one image. Expected not.',
        );
    }

    /**
     * @Given I remove the image
     * @Given I remove this image
     */
    public function iRemoveTheImage()
    {
        $this->resolveCurrentPage()->pressRemoveImageButton();
    }

    /**
     * @Then /^remove image button and image preview are (hidden|visible)$/
     */
    public function removeImageButtonAndImagePreviewAreHidden(string $visibility)
    {
        $page = $this->resolveCurrentPage();
        if ($visibility === 'hidden') {
            Assert::false(
                $page->removeButtonIsVisible(),
                'The button is visible',
            );
            Assert::false(
                $page->imagePreviewIsVisible(),
                'The button is visible',
            );
        }
    }

    /**
     * @Given /^I should see icon image file input$/
     */
    public function iShouldSeeIconImageFileInput()
    {
        Assert::true(
            $this->resolveCurrentPage()->isIconImageField(),
        );
    }

    /**
     * @When /^I press button to remove "([^"]*)"$/
     */
    public function iPressButtonToRemove(string $itemTitle)
    {
        $this->indexPage->deleteResourceOnPage(['title' => $itemTitle]);
    }

    /**
     * @Given /^I should see custom link input in "([^"]*)"$/
     * @Given /^I should see custom link input in "([^"]*)" and "([^"]*)"$/
     */
    public function iShouldSeeCustomLinkInputIn(...$locales)
    {
        foreach ($locales as $locale) {
            $localeCode = $this->localeConverter->convertNameToCode($locale);
            Assert::true(
                $this->resolveCurrentPage()->isCustomLinkFieldIn($localeCode),
                sprintf('The field not found on locale %s', $locale),
            );
        }
    }

    /**
     * @Then /^I should be notified the custom URL in "([^"]*)" is not valid with the message: "([^"].*[^"])"$/
     * @Then /^I should be notified the custom URL in "([^"]*)" can not be blank with the message: "([^"].*[^"])"$/
     */
    public function iShouldBeNotifiedTheCustomURLIsNotValidWithTheMessage(string $locale, string $expectedMessage)
    {
        $localeCode = $this->localeConverter->convertNameToCode($locale);
        $validationMessage = $this->resolveCurrentPage()->getErrorMessageOfCustomLinkField($localeCode);

        Assert::same(
            $validationMessage,
            $expectedMessage,
        );
    }

    /**
     * @return CreatePageInterface|UpdatePageInterface
     */
    protected function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->pageResolver->getCurrentPageWithForm([
            $this->createPage,
            $this->updatePage,
        ]);
    }

    protected function resolveFieldFromString(string $field): string
    {
        $field = strtolower($field); //set string to lower
        $field = ucwords($field); //set each first letter to uppercase
        $field = preg_replace('/\s/', '', $field); //remove all blank space
        $field = lcfirst($field); // set first letter to lower case

        return $field;
    }
}
