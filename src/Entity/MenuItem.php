<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Wemea\SyliusMenuPlugin\Model\MenuItem as BaseMenuItem;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;

class MenuItem extends BaseMenuItem implements MenuItemInterface
{
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    /** @var int|null */
    protected $id;

    /** @var ImageInterface|null */
    protected $image;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        /** @var MenuItemTranslationInterface $translation */
        $translation = $this->getTranslation();

        return $translation->getTitle();
    }

    public function setTitle(?string $title): void
    {
        /** @var MenuItemTranslationInterface $translation */
        $translation = $this->getTranslation();
        $translation->setTitle($title);
    }

    protected function createTranslation(): MenuItemTranslationInterface
    {
        return new MenuItemTranslation();
    }

    public function getDescription(): ?string
    {
        /** @var MenuItemTranslationInterface $translation */
        $translation = $this->getTranslation();

        return $translation->getDescription();
    }

    public function setDescription(?string $description): void
    {
        /** @var MenuItemTranslationInterface $translation */
        $translation = $this->getTranslation();
        $translation->setDescription($description);
    }

    public function getImage(): ?ImageInterface
    {
        return $this->image;
    }

    public function setImage(?ImageInterface $image): void
    {
        if ($image != null) {
            $image->setOwner($this);
        }
        $this->image = $image;
    }

    public function hasImage(): bool
    {
        return $this->image !== null;
    }
}
