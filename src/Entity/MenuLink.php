<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Entity;

use Wemea\SyliusMenuPlugin\Model\MenuLink as BaseMenuLink;
use Sylius\Component\Resource\Model\TranslatableTrait;

class MenuLink extends BaseMenuLink implements MenuLinkInterface
{
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    /** @var int|null */
    protected $id;

    public function __construct()
    {
        //empty constructor to avoid to call TranslatableTrait::__construct by default
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomLink(): ?string
    {
        /** @var MenuLinkTranslationInterface $translation */
        $translation = $this->getTranslation();

        return $translation->getCustomLink();
    }

    public function setCustomLink(?string $link): void
    {
        /** @var MenuLinkTranslationInterface $translation */
        $translation = $this->getTranslation();
        $translation->setCustomLink($link);
    }

    protected function createTranslation(): MenuLinkTranslationInterface
    {
        return new MenuLinkTranslation();
    }

    public static function getLinkProperties(): array
    {
        return array_merge(parent::getLinkProperties(), [
            'translations',
        ]);
    }
}
