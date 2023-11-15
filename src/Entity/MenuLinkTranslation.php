<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Sylius\Component\Resource\Model\AbstractTranslation;

class MenuLinkTranslation extends AbstractTranslation implements MenuLinkTranslationInterface
{
    /** @var int|null */
    protected $id;

    /** @var string|null */
    protected $customLink;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomLink(): ?string
    {
        return $this->customLink;
    }

    public function setCustomLink(?string $link): void
    {
        $this->customLink = $link;
    }
}
