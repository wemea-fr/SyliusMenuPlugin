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

namespace Wemea\SyliusMenuPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class LinkIsDefined extends Constraint
{
    public string $messageLinkIsDefined = 'wemea_sylius_menu.validators.link_is_defined';

    public string$messageLinkNotBlank = 'wemea_sylius_menu.form.menu_link_translation.custom_link.not_blank';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
