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

namespace Wemea\SyliusMenuPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class MenuCustomLinkTranslationType extends AbstractResourceType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customLink', null, [
                'label' => 'wemea_sylius_menu.form.menu_link.label.custom',
            ])
        ;
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return 'wemea_sylius_menu_menu_link_translation';
    }
}
