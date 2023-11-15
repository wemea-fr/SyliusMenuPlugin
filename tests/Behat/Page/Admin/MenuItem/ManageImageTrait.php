<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Page\Admin\MenuItem;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Webmozart\Assert\Assert;

trait ManageImageTrait
{
    public function isIconImageField(): bool
    {
        try {
            $this->getElement('icon_file_input');

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }

    public function attachImage(string $image): void
    {
        $filesPath = $this->getParameter('files_path');
        $fileInput = $this->getElement('icon_file_input');
        $absoluteFilePath = $filesPath . $image;

        Assert::fileExists(
            $absoluteFilePath,
            sprintf('the file "%s" not found', $image),
        );

        $fileInput->attachFile($absoluteFilePath);
    }

    public function pressRemoveImageButton(): void
    {
        try {
            /** @var NodeElement $button */
            $button = $this->getElement('remove_file_button');
            //try to click on label
            $button->click();
        } catch (UnsupportedDriverActionException $e) {
            //if js is not support, use checkbox
            /** @var NodeElement $checkbox */
            $checkbox = $this->getElement('remove_file_checkbox');
            $checkbox->check();
        }
    }

    public function removeButtonIsVisible(): bool
    {
        /** @var NodeElement $button */
        $button = $this->getElement('remove_file_button');

        return $button->isVisible();
    }

    public function imagePreviewIsVisible(): bool
    {
        /** @var NodeElement $imagePreviewElement */
        $imagePreviewElement = $this->getElement('image_preview');

        return $imagePreviewElement->isVisible();
    }

    protected function getImageDefinedElements(): array
    {
        return [
            'icon_file_input' => '#wemea_sylius_menu_menu_item_image_file',
            'remove_file_checkbox' => '#wemea_sylius_menu_menu_item_image_remove_image',
            'remove_file_button' => 'label[for="wemea_sylius_menu_menu_item_image_remove_image"]',
            'image_preview' => '#wemea_sylius_menu_menu_item_image_preview',
        ];
    }
}
