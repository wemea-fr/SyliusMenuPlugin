<?php

declare(strict_types=1);

namespace spec\Wemea\SyliusMenuPlugin\Entity;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\Image;
use Sylius\Component\Core\Model\ImageInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuItemImage;

class MenuItemImageSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(MenuItemImage::class);
    }

    function it_resolve_depedencies(): void
    {
        $this->shouldHaveType(Image::class);
        $this->shouldImplement(ImageInterface::class);
    }
}
