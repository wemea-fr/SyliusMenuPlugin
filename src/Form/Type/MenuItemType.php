<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Form\Type;

use Wemea\SyliusMenuPlugin\Entity\MenuItemInterface;
use Wemea\SyliusMenuPlugin\Factory\MenuLinkFactoryInterface;
use Wemea\SyliusMenuPlugin\Model\MenuLinkInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MenuItemType extends AbstractResourceType
{
    /** @var string|MenuLinkInterface */
    protected $linkClass;

    /** @var MenuLinkFactoryInterface */
    protected $menuLinkFactory;

    public function __construct(
        string $dataClass,
        string $linkClass,
        MenuLinkFactoryInterface $menuLinkFactory,
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
        $this->linkClass = $linkClass;
        $this->menuLinkFactory = $menuLinkFactory;
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

        foreach ($this->dataClass::AVAILABLE_TARGETS as $target) {
            $choices['wemea_sylius_menu.ui.target_label.' . $target] = $target;
        }

        return $choices;
    }
}
