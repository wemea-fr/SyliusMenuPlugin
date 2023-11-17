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
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Factory\MenuLinkFactoryInterface;

class MenuItemType extends AbstractResourceType
{
    /**
     * @param string[] $validationGroups
     */
    public function __construct(
        string $dataClass,
        protected string $linkClass,
        protected MenuLinkFactoryInterface $menuLinkFactory,
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //Create link if item has not it
        /** @phpstan-ignore-next-line */
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var MenuItemInterface $menuItem */
            $menuItem = $event->getData();
            if (!$menuItem->hasLink()) {
                $menuLink = $this->menuLinkFactory->createForMenuItem($menuItem);
                $menuItem->setLink($menuLink);
            }
        });

        $builder
            ->add('target', ChoiceType::class, [
                'label' => 'wemea_sylius_menu.ui.target',
                'choices' => $this->getTargetChoices(),
            ])
            ->add('priority', null, [
                'label' => 'sylius.ui.priority',
            ])
            ->add('cssClasses', null, [
                'label' => 'wemea_sylius_menu.ui.css_classes',
            ])
            ->add('link', MenuLinkType::class)
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => MenuItemTranslationType::class,
            ])
            ->add('image', MenuItemImageType::class, [
                'label' => 'wemea_sylius_menu.ui.item_icon',
                'required' => false,
            ])
        ;
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return 'wemea_sylius_menu_menu_item';
    }

    protected function getTargetChoices(): array
    {
        $choices = [];

        /** @var string $target */
        foreach ($this->dataClass::AVAILABLE_TARGETS as $target) {
            $choices['wemea_sylius_menu.ui.target_label.' . $target] = $target;
        }

        return $choices;
    }
}
