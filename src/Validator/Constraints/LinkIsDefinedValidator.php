<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Validator\Constraints;

use Doctrine\Common\Collections\Collection;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkTranslationInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class LinkIsDefinedValidator extends ConstraintValidator
{
    /** @var string */
    protected $defaultMessageLinkIsDefined;

    /** @phpstan-ignore-next-line */
    public function validate($menuLink, Constraint $constraint): void
    {
        if (!$constraint instanceof LinkIsDefined) {
            throw new UnexpectedTypeException($constraint, LinkIsDefined::class);
        }

        if (!$menuLink instanceof MenuLinkInterface) {
            throw new UnexpectedValueException($menuLink, MenuLinkInterface::class);
        }
        //init message
        $this->defaultMessageLinkIsDefined = $constraint->messageLinkIsDefined;

        //Is null if all allowed properties are null
        if (null === $menuLink->getType()) {
            $this->buildViolationOnResourceLink();
        }

        //add custom validation constraint on translation collection to validate the custom link
        if ($menuLink->getType() === MenuLinkInterface::CUSTOM_LINK_PROPERTY) {
            /** @var Collection|MenuLinkTranslationInterface[] $customLinkTranslations */
            $customLinkTranslations = $menuLink->getLinkResource();

            if (count($customLinkTranslations) < 1) {
                $this->buildViolationOnResourceLink();
            }

            foreach ($customLinkTranslations as $translation) {
                //Is not valid if each translation is null or eq to empty string
                if (null === $translation->getCustomLink() || trim($translation->getCustomLink()) === '') {
                    //Build custom violation on the target field
                    $this->context->buildViolation($constraint->messageLinkNotBlank)
                        ->atPath(sprintf('translations[%s].customLink', $translation->getLocale()))
                        ->addViolation()
                    ;
                }
            }
        }
    }

    protected function buildViolationOnResourceLink(?string $message = null): void
    {
        $message = $message ?? $this->defaultMessageLinkIsDefined;

        $this->context->buildViolation($message)
            ->atPath('linkIsDefined')
            ->addViolation()
        ;
    }
}
