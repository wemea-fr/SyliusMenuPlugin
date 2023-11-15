<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Unit\PluginUnitTest\Factory;

use Wemea\SyliusMenuPlugin\Factory\MenuFactory;
use Wemea\SyliusMenuPlugin\Model\VisibilityTraitInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Resource\Factory\Factory;

class MenuFactoryTest extends TestCase
{
    protected const MENU_CLASS_NAME = \Wemea\SyliusMenuPlugin\Entity\Menu::class;

    /**
     * @test
     */
    public function itReturnANewMenu(): void
    {
        $factory = $this->getMenuFactory();
        $menu = $factory->createNew();

        $this->assertInstanceOf(
            self::MENU_CLASS_NAME,
            $menu
        );

        //assert default values
        $this->assertTrue(
            $menu->isEnabled(),
            'Menu is disabled. Should be enabled by default'
        );

        $this->assertEquals(
            VisibilityTraitInterface::PUBLIC_VISIBILITY,
            $menu->getVisibility(),
            'Menu Should be public by default'
        );
    }

    /**
     * @test
     * @dataProvider createWithCodeStateVisibilityAndChannelsSuccessDataProvider
     */
    public function createWithCodeStateVisibilityAndChannelsSuccess(string $code, bool $disabled, bool $private, array $channels)
    {
        $factory = $this->getMenuFactory();

        $menu = $factory->createWithCodeStateVisibilityAndChannels($code, $disabled, $private, $channels);

        //assert values
        $this->assertEquals(
            $code,
            $menu->getCode()
        );

        //assert inverse
        if ($disabled) {
            $this->assertFalse(
                $menu->isEnabled(),
                'The menu should be disabled'
            );
        } else {
            $this->assertTrue(
                $menu->isEnabled(),
                'The menu should be enabled'
            );
        }

        if ($private) {
            $this->assertEquals(
                VisibilityTraitInterface::PRIVATE_VISIBILITY,
                $menu->getVisibility(),
                'The menu should be private'
            );
        } else {
            $this->assertEquals(
                VisibilityTraitInterface::PUBLIC_VISIBILITY,
                $menu->getVisibility(),
                'The menu should be public'
            );
        }

        //Assert Channels
        $expectedChannelsCodes = array_map(function (ChannelInterface $channel) {
            return $channel->getCode();
        }, $channels);

        $currentChannelsCodes = [];
        //do for each to get each channels from collection
        foreach ($menu->getChannels() as $channel) {
            $currentChannelsCodes[] = $channel->getCode();
        }
        //sort array to have same
        //assert equal codes of channels
        $this->assertArrayAreEquals(
            $expectedChannelsCodes,
            $currentChannelsCodes
        );
    }

    public function createWithCodeStateVisibilityAndChannelsSuccessDataProvider(): array
    {
        $data = [];

        $data[] = [
            'code' => 'new_menu',
            'disabled' => false,
            'private' => false,
            'channels' => [],
        ];

        //test with disabled menu
        $data[] = [
            'code' => 'new_menu',
            'disabled' => true,
            'private' => false,
            'channels' => [],
        ];

        //test with private menu
        $data[] = [
            'code' => 'new_menu',
            'disabled' => false,
            'private' => true,
            'channels' => [],
        ];

        //test with disabled and private menu
        $data[] = [
            'code' => 'new_menu',
            'disabled' => true,
            'private' => true,
            'channels' => [],
        ];

        //test with one channel
        $channel1 = new Channel();
        $channel1->setCode('channel_1');

        $data[] = [
            'code' => 'new_menu',
            'disabled' => false,
            'private' => false,
            'channels' => [$channel1],
        ];

        //test with more one channel
        $channel2 = new Channel();
        $channel2->setCode('channel_2');

        $data[] = [
            'code' => 'new_menu',
            'disabled' => true,
            'private' => false,
            'channels' => [$channel1, $channel2],
        ];

        return $data;
    }

    protected function getMenuFactory()
    {
        $defaultFactory = new Factory(self::MENU_CLASS_NAME);

        return new MenuFactory($defaultFactory);
    }

    protected function assertArrayAreEquals(array $expected, array $value)
    {
        $this->assertEquals(
            count($expected),
            count($value),
            'Both array has not the same size'
        );

        for ($i = 0; $i < count($expected), $i++;) {
            $this->assertEquals(
                $expected[$i],
                $value[$i],
                sprintf('Value at index %d is different to expected value', $i)
            );
        }
    }
}
