<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Helper;

interface ImageMatcherHelperInterface
{
    public function imageMatchToSyliusFixture(string $imageName, string $currentRelativeFilePath): void;

    public function liipImagineGeneratedImageMatchToSyliusFixture(string $imageName, string $absoluteLiipImagePath): void;

    public function imageIsRemoved(?string $baseFilePath): void;
}
