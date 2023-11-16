<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MenuType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'disabled' => true,
                'label' => 'sylius.ui.code',
            ])
            ->add('enabled', null, [
                'label' => 'sylius.ui.enabled',
            ])
            ->add('visibility', ChoiceType::class, [
                'label' => 'wemea_sylius_menu.ui.visibility',
                'choices' => $this->getVisibilityChoices(),
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => MenuTranslationType::class,
            ])

            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'sylius.ui.channels',
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'wemea_sylius_menu_menu';
    }

    protected function getVisibilityChoices(): array
    {
        $choices = [];

        /**
         * @var string $value
         * @var string $code
         */
        foreach ($this->dataClass::VISIBILITY_CODE as $value => $code) {
            $choices['wemea_sylius_menu.ui.visibility_label.' . $code] = $value;
        }

        return $choices;
    }
}
