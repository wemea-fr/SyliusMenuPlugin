<?php

declare(strict_types=1);

/*
 * This file is part of Wemea Menu plugin for Sylius.
 *
 * (c) Wemea (wemea.fr)
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Helper;

interface ImageMatcherHelperInterface
{
    public function imageMatchToSyliusFixture(string $imageName, string $currentRelativeFilePath): void;

    public function liipImagineGeneratedImageMatchToSyliusFixture(string $imageName, string $absoluteLiipImagePath): void;

    public function imageIsRemoved(?string $baseFilePath): void;
}
