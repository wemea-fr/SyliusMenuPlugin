<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Unit\PluginUnitTest\Entity;

use Wemea\SyliusMenuPlugin\Entity\Menu;
use Wemea\SyliusMenuPlugin\Entity\MenuInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuItem;
use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    /**
     * @test
     * @dataProvider getMenuItemsOrderByPrioritySuccessDataProvider
     */
    public function getMenuItemsOrderByPrioritySuccess(array $itemInput, array $expectedOrder)
    {
        $menu = $this->createMenuWithItem($itemInput);

        $menuItemsOrdered = $menu->getMenuItemsOrderByPriority();
        $this->assertIsArray($menuItemsOrdered);

        //Use custom index to have index of current order and not the 'real' index of element give by foreach ($menuItemsOrdered as $index => $item)
        $index = 0;
        /** @var MenuItemInterface $item */
        foreach ($menuItemsOrdered as $item) {
            $this->assertEquals(
                $expectedOrder[$index],
                $item->getTitle(),
                sprintf('There is "%s" at position %d. Expected to have "%s"', $item->getTitle(), $index, $expectedOrder[$index])
            );
            $index += 1;
        }
    }

    public function getMenuItemsOrderByPrioritySuccessDataProvider(): array
    {
        $data = [];

//        //test without any priority
        $data[] = [
            'item_input' => [
                [
                    'title' => 'item_1',
                    'priority' => null,
                ],
                [
                    'title' => 'item_2',
                    'priority' => null,
                ],
                [
                    'title' => 'item_3',
                    'priority' => null,
                ],
            ],
            'expected_order' => [
                'item_1',
                'item_2',
                'item_3',
            ],
        ];

        //test with full priority setting
        $data[] = [
            'item_input' => [
                [
                    'title' => 'item_1',
                    'priority' => 25,
                ],
                [
                    'title' => 'item_2',
                    'priority' => 0,
                ],
                [
                    'title' => 'item_3',
                    'priority' => 10,
                ],
            ],
            'expected_order' => [
                'item_2',
                'item_3',
                'item_1',
            ],
        ];

//        //test with some negative value
        $data[] = [
            'item_input' => [
                [
                    'title' => 'item_1',
                    'priority' => 25,
                ],
                [
                    'title' => 'item_2',
                    'priority' => 0,
                ],
                [
                    'title' => 'item_3',
                    'priority' => -5,
                ],
            ],
            'expected_order' => [
                'item_3',
                'item_2',
                'item_1',
            ],
        ];
//
//        //test with some defined priority and null values
        $data[] = [
            'item_input' => [
                [
                    'title' => 'item_1',
                    'priority' => null,
                ],
                [
                    'title' => 'item_2',
                    'priority' => null,
                ],
                [
                    'title' => 'item_3',
                    'priority' => 10,
                ],
            ],
            'expected_order' => [
                'item_3',
                'item_1',
                'item_2',
            ],
        ];

        $data[] = [
            'item_input' => [
                [
                    'title' => 'item_1',
                    'priority' => null,
                ],
                [
                    'title' => 'item_2',
                    'priority' => 5,
                ],
                [
                    'title' => 'item_3',
                    'priority' => 10,
                ],
            ],
            'expected_order' => [
                'item_2',
                'item_3',
                'item_1',
            ],
        ];

        $data[] = [
            'item_input' => [
                [
                    'title' => 'item_1',
                    'priority' => 20,
                ],
                [
                    'title' => 'item_2',
                    'priority' => null,
                ],
                [
                    'title' => 'item_3',
                    'priority' => -20,
                ],
            ],
            'expected_order' => [
                'item_3',
                'item_1',
                'item_2',
            ],
        ];

        return $data;
    }

    protected function createMenuWithItem(array $itemInput): MenuInterface
    {
        $menu = new Menu();
        foreach ($itemInput  as $itemProperties) {
            $menuItem = new MenuItem();
            $menuItem->setCurrentLocale('en_US');
            $menuItem->setTitle($itemProperties['title']);
            $menuItem->setPriority($itemProperties['priority']);
            $menu->addMenuItem($menuItem);
        }

        return $menu;
    }
}
