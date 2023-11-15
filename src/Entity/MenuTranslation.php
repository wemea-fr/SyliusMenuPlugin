<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Sylius\Component\Resource\Model\AbstractTranslation;

class MenuTranslation extends AbstractTranslation implements MenuTranslationInterface
{
    /** @var string|null */
    protected $title;

    /** @var int|null */
    protected $id;

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
}
