<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Sylius\Component\Resource\Model\AbstractTranslation;

class MenuItemTranslation extends AbstractTranslation implements MenuItemTranslationInterface
{
    /** @var int|null */
    protected $id;

    /** @var string|null */
    protected $title;

    /** @var string|null */
    protected $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
