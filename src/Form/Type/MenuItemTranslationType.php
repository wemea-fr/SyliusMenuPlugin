<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class MenuItemTranslationType extends AbstractResourceType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'sylius.ui.title',
            ])
            ->add('description', null, [
                'required' => false,
                'label' => 'sylius.ui.description',
            ])
        ;
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return 'wemea_sylius_menu_menu_item_translation';
    }
}
