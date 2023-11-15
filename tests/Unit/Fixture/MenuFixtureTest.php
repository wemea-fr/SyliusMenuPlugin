<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Unit\PluginUnitTest\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use Wemea\SyliusMenuPlugin\Fixture\Factory\MenuExampleFactory;
use Wemea\SyliusMenuPlugin\Fixture\MenuFixture;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;

class MenuFixtureTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @test
     */
    public function menu_are_optional(): void
    {
        $this->assertConfigurationIsValid([[]], 'custom');
    }

    /**
     * @test
     */
    public function menu_code_is_required(): void
    {
        $this->assertConfigurationIsInvalid([['custom' => [[]]]], 'code');
    }

    /**
     * @test
     */
    public function menu_state_is_optional_and_accept_boolean_values(): void
    {
        $this->assertConfigurationIsValid([['custom' => [[]]]], 'custom.*.enabled');
        $this->assertConfigurationIsValid([['custom' => [['enabled' => true]]]], 'custom.*.enabled');
        $this->assertConfigurationIsValid([['custom' => [['enabled' => false]]]], 'custom.*.enabled');

        $this->assertPartialConfigurationIsInvalid([['custom' => [['enabled' => 'false']]]], 'custom.*.enabled', 'string');
    }

    /**
     * @test
     */
    public function menu_visibility_is_optional_but_can_not_be_empty(): void
    {
        $this->assertConfigurationIsValid([['custom' => [[]]]], 'custom.*.visibility');
        $this->assertConfigurationIsValid([['custom' => [['visibility' => 'public']]]], 'custom.*.visibility');

        $this->assertPartialConfigurationIsInvalid([['custom' => [['visibility' => '']]]], 'custom.*.visibility');
    }

    /**
     * @test
     */
    public function menu_visibility_accept_defined_values(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['visibility' => 'public']]]], 'custom.*.visibility');
        $this->assertConfigurationIsValid([['custom' => [['visibility' => 'private']]]], 'custom.*.visibility');

        $this->assertPartialConfigurationIsInvalid([['custom' => [['visibility' => 'another_value']]]], 'custom.*.visibility', 'Permissible values: "public", "private"');
    }

    /**
     * @test
     */
    public function menu_translations_are_optional_but_can_not_be_empty(): void
    {
        $this->assertConfigurationIsValid([['custom' => [[]]]], 'custom.*.translations');
        $this->assertConfigurationIsValid([['custom' => [['translations' => ['en_US' => ['title' => 'default title']]]]]], 'custom.*.translations');

        $this->assertPartialConfigurationIsInvalid([['custom' => [['translations' => []]]]], 'custom.*.translations', 'at least 1 element');
    }

    /**
     * @test
     */
    public function menu_channels_are_optional_and_accept_array_of_string(): void
    {
        $this->assertConfigurationIsValid([['custom' => [[]]]], 'custom.*.channels');
        $this->assertConfigurationIsValid([['custom' => [['channels' => []]]]], 'custom.*.channels');
        $this->assertConfigurationIsValid([['custom' => [['channels' => ['one_channel', 'another_channel']]]]], 'custom.*.channels');

        $this->assertPartialConfigurationIsInvalid([['custom' => [['channels' => ['one_channel' => []]]]]], 'custom.*.channels');
    }

    /**
     * @test
     */
    public function menu_items_are_optional_but_can_not_be_empty(): void
    {
        $this->assertConfigurationIsValid([['custom' => [[]]]], 'custom.*.items');
        $this->assertPartialConfigurationIsInvalid([['custom' => [['items' => []]]]], 'custom.*.items', 'at least 1 element');
    }

    /**
     * @test
     */
    public function menu_item_target_is_optional(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['items' => [[]]]]]], 'custom.*.items.*.target');
        $this->assertConfigurationIsValid([['custom' => [['items' => [['target' => null]]]]]], 'custom.*.items.*.target');
    }

    /**
     * @test
     */
    public function menu_item_target_accept_defined_values(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['items' => [['target' => '_self']]]]]], 'custom.*.items.*.target');
        $this->assertConfigurationIsValid([['custom' => [['items' => [['target' => '_blank']]]]]], 'custom.*.items.*.target');

        $this->assertPartialConfigurationIsInvalid([['custom' => [['items' => [['target' => '_bad_target']]]]]], 'custom.*.items.*.target', 'Permissible values: "_self", "_blank"');
    }

    /**
     * @test
     */
    public function menu_item_priority_is_optional(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['items' => [[]]]]]], 'custom.*.items.*.priority');
        $this->assertConfigurationIsValid([['custom' => [['items' => [['priority' => 20]]]]]], 'custom.*.items.*.priority');
    }

    /**
     * @test
     */
    public function menu_item_css_classes_is_optional(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['items' => [[]]]]]], 'custom.*.items.*.css_classes');
        $this->assertConfigurationIsValid([['custom' => [['items' => [['css_classes' => '']]]]]], 'custom.*.items.*.css_classes');
        $this->assertConfigurationIsValid([['custom' => [['items' => [['css_classes' => 'link red']]]]]], 'custom.*.items.*.css_classes');
    }

    /**
     * @test
     */
    public function menu_item_translations_are_optional_but_can_not_be_empty(): void
    {
        $this->assertConfigurationIsValid([['custom' => [[]]]], 'custom.*.items.*.translations');
        $this->assertConfigurationIsValid([['custom' => [['items' => [['translations' => ['en_US' => ['title' => 'default title']]]]]]]], 'custom.*.items.*.translations');

        $this->assertPartialConfigurationIsInvalid([['custom' => [['items' => [['translations' => []]]]]]], 'custom.*.items.*.translations', 'at least 1 element');
    }

    /**
     * @test
     */
    public function menu_translation_title_can_not_be_empty_and_description_is_optional(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['items' => [['translations' => ['en_US' => ['title' => 'default title', 'description' => 'one description']]]]]]]], 'custom.*.items.*.translations');
        $this->assertConfigurationIsValid([['custom' => [['items' => [['translations' => ['en_US' => ['title' => 'default title']]]]]]]], 'custom.*.items.*.translations');

        $this->assertPartialConfigurationIsInvalid([['custom' => [['items' => [['translations' => ['en_US' => []]]]]]]], 'custom.*.items.*.translations.*.title');
        $this->assertPartialConfigurationIsInvalid([['custom' => [['items' => [['translations' => ['en_US' => ['title' => '']]]]]]]], 'custom.*.items.*.translations.*.title');
        $this->assertPartialConfigurationIsInvalid([['custom' => [['items' => [['translations' => ['en_US' => ['description' => '']]]]]]]], 'custom.*.items.*.translations.*.description');
    }

    /**
     * @test
     */
    public function menu_link_is_required(): void
    {
        $this->assertPartialConfigurationIsInvalid([['custom' => [['items' => [[]]]]]], 'custom.*.items.*.link', 'link');
    }

    /**
     * @test
     */
    public function menu_custom_link_is_optional_but_can_not_be_empty(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['items' => [['link' => []]]]]]], 'custom.*.items.*.link.custom_link');
        $this->assertPartialConfigurationIsInvalid([['custom' => [['items' => [['link' => ['custom_link' => []]]]]]]], 'custom.*.items.*.link.custom_link', 'at least 1 element');
    }

    /**
     * @test
     */
    public function menu_custom_link_translation_is_optional_but_can_not_be_empty(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['items' => [['link' => ['custom_link' => ['en_US' => '/my/path']]]]]]]], 'custom.*.items.*.link.custom_link.*');
        $this->assertPartialConfigurationIsInvalid([['custom' => [['items' => [['link' => ['custom_link' => ['en_US' => '']]]]]]]], 'custom.*.items.*.link.custom_link.*');
    }

    /**
     * @test
     */
    public function menu_product_link_is_optional_but_can_not_be_empty(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['items' => [['link' => ['product_code' => 'the_product_code']]]]]]], 'custom.*.items.*.link.product_code');
        $this->assertPartialConfigurationIsInvalid([['custom' => [['items' => [['link' => ['product_code' => '']]]]]]], 'custom.*.items.*.link.product_code');
    }

    /**
     * @test
     */
    public function menu_taxon_link_is_optional_but_can_not_be_empty(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['items' => [['link' => ['taxon_code' => 'DEFAULT_TAXON_CODE']]]]]]], 'custom.*.items.*.link.taxon_code');
        $this->assertPartialConfigurationIsInvalid([['custom' => [['items' => [['link' => ['taxon_code' => '']]]]]]], 'custom.*.items.*.link.taxon_code');
    }

    protected function getConfiguration()
    {
        $menuExampleFactoryMock = $this->getMockBuilder(MenuExampleFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        return new MenuFixture(
            $this->getMockBuilder(EntityManagerInterface::class)->getMock(),
            $menuExampleFactoryMock,
            'en_US'
        );
    }
}
