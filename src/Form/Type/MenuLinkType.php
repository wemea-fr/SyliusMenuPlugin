<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonAutocompleteChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkInterface;

final class MenuLinkType extends AbstractResourceType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            //map link on field
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event): void {
                /** @var MenuLinkInterface $item */
                $item = $event->getData();

                if ($item->getType() !== null && $item->getLinkResource() !== null) {
                    $event->getForm()->get('type')->setData($item->getType());
                    /** @psalm-suppress PossiblyNullArgument */
                    $event->getForm()->get($item->getType())->setData($item->getLinkResource());
                }
            })
            ->add('type', ChoiceType::class, [
                'label' => 'wemea_sylius_menu.ui.link_type',
                'choices' => $this->getLinkTypeChoices(),
                'mapped' => false,
            ])
            ->add('product', ProductAutocompleteChoiceType::class, [
                'label' => 'wemea_sylius_menu.form.menu_link.label.product',
                'mapped' => false,
            ])
            ->add('taxon', TaxonAutocompleteChoiceType::class, [
                'label' => 'wemea_sylius_menu.form.menu_link.label.taxon',
                'mapped' => false,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => MenuCustomLinkTranslationType::class,
                'mapped' => false,
            ])
            //just hidden field to map error
            ->add('linkIsDefined', HiddenType::class, [
                'error_bubbling' => false,
                'mapped' => false,
            ])

            //create new translation collection if translation does not exist and is expected resource
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                /** @var MenuLinkInterface $item */
                $item = $event->getForm()->getData();

                if ($item->getType() !== MenuLinkInterface::CUSTOM_LINK_PROPERTY) {
                    $item->setLinkResource(MenuLinkInterface::CUSTOM_LINK_PROPERTY, new ArrayCollection());
                }
            })

            //Set data from type
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
                try {
                    /** @var string $type */
                    $type = $event->getForm()->get('type')->getData();
                    /** @psalm-suppress MixedAssignment */
                    $linkValue = $event->getForm()->get($type)->getData();

                    /** @var MenuLinkInterface $link */
                    $link = $event->getData();
                    /**
                     * @phpstan-ignore-next-line
                     *
                     * @psalm-suppress MixedArgument
                     */
                    $link->setLinkResource($type, $linkValue);
                } catch (\Exception $e) {
                    // it crash if $type is not valid.
                    // Do a simple return. Error is automapping on type field
                    return;
                }
            })
        ;
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return 'wemea_sylius_menu_menu_link';
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @psalm-suppress MixedArrayAssignment */
        $view->vars['allowed_link_properties'] = $this->dataClass::getLinkProperties();
    }

    protected function getLinkTypeChoices(): array
    {
        $choices = [];

        /** @var string $property */
        foreach ($this->dataClass::getLinkProperties() as $property) {
            $choices['wemea_sylius_menu.ui.link_type_label.' . $property] = $property;
        }

        return $choices;
    }
}
