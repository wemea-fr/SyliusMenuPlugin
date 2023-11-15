<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Helper;

use Wemea\SyliusMenuPlugin\Entity\MenuInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

interface CreateMenuHelperInterface
{
    public function createMenuFromCommandOption(SymfonyStyle $io, string $code, bool $disabled, bool $private, ?string $channelsCode): MenuInterface;
}
