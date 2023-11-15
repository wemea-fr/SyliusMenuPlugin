<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LinkIsDefined extends Constraint
{
    /** @var string */
    public $messageLinkIsDefined = 'wemea_sylius_menu.validators.link_is_defined';

    /** @var string */
    public $messageLinkNotBlank = 'wemea_sylius_menu.form.menu_link_translation.custom_link.not_blank';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
