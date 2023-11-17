<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Helper;

use Symfony\Component\Console\Style\SymfonyStyle;
use Wemea\SyliusMenuPlugin\Entity\MenuInterface;

interface CreateMenuHelperInterface
{
    public function createMenuFromCommandOption(SymfonyStyle $io, string $code, bool $disabled, bool $private, ?string $channelsCode): MenuInterface;
}
