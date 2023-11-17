<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Sylius\Component\Resource\Model\TranslatableTrait;
use Wemea\SyliusMenuPlugin\Model\Menu as BaseMenu;

class Menu extends BaseMenu implements MenuInterface
{
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    /** @var int|null */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->initializeTranslationsCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        /** @var MenuTranslationInterface $translation */
        $translation = $this->getTranslation();

        return $translation->getTitle();
    }

    public function setTitle(?string $title): void
    {
        /** @var MenuTranslationInterface $translation */
        $translation = $this->getTranslation();
        $translation->setTitle($title);
    }

    protected function createTranslation(): MenuTranslationInterface
    {
        return new MenuTranslation();
    }

    /**
     * Custom function to order by priority ASC but with null value at the end
     */
    public function getMenuItemsOrderByPriority(): array
    {
        //TODO : find better way to do this
        /** @var MenuItem[] $items */
        $items = $this->menuItems->toArray();

        /** @phpstan-ignore-next-line */
        uasort($items, function (MenuItem $a, MenuItem $b) {
            $priorityA = $a->getPriority();
            $priorityB = $b->getPriority();

            if ($priorityA === null && $priorityB === null) {
                return 0;
            }

            if ($priorityA === null) {
                return 1;
            }

            if ($priorityB === null) {
                return -1;
            }

            return $priorityA - $priorityB;
        });

        return $items;
    }
}
