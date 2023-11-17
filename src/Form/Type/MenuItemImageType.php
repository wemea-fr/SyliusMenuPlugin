<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Form\Type;

use Sylius\Bundle\CoreBundle\Form\Type\ImageType;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MenuItemImageType extends ImageType
{
    /**
     * @param string[] $validationGroups
     */
    public function __construct(
        protected ImageUploaderInterface $imageUploader,
        protected RepositoryInterface $menuItemImageRepository,
        string $dataClass,
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('type')
            //field to remove last image
            ->add('remove_image', CheckboxType::class, [
                'mapped' => false,
                'label' => null,
            ])

            ->addEventListener(FormEvents::SUBMIT, [$this, 'removeImage'])
        ;
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'wemea_sylius_menu_menu_item_image';
    }

    public function removeImage(FormEvent $event): void
    {
        if ($event->getForm()->get('remove_image')->getData() != null) {
            $image = $event->getData();
            if (!$image instanceof ImageInterface || $image->getPath() === null) {
                $event->setData(null);

                return;
            }
            /** @psalm-suppress PossiblyNullArgument */
            $this->imageUploader->remove($image->getPath());
            $this->menuItemImageRepository->remove($image);
            $event->setData(null);
        }
    }
}
