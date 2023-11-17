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

namespace Wemea\SyliusMenuPlugin\Validator\Constraints;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkInterface;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkTranslationInterface;

class LinkIsDefinedValidator extends ConstraintValidator
{
    /**
     * @phpstan-ignore-next-line
     *
     * @psalm-suppress MissingParamType
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof LinkIsDefined) {
            throw new UnexpectedTypeException($constraint, LinkIsDefined::class);
        }

        if (!$value instanceof MenuLinkInterface) {
            throw new UnexpectedValueException($value, MenuLinkInterface::class);
        }
        //init message
        $defaultMessageLinkIsDefined = $constraint->messageLinkIsDefined;

        //Is null if all allowed properties are null
        if (null === $value->getType()) {
            $this->buildViolationOnResourceLink($defaultMessageLinkIsDefined);
        }

        //add custom validation constraint on translation collection to validate the custom link
        if ($value->getType() === MenuLinkInterface::CUSTOM_LINK_PROPERTY) {
            /** @var Collection|MenuLinkTranslationInterface[] $customLinkTranslations */
            $customLinkTranslations = $value->getLinkResource();

            if (count($customLinkTranslations) < 1) {
                $this->buildViolationOnResourceLink($defaultMessageLinkIsDefined);
            }

            /** @var MenuLinkTranslationInterface $translation */
            foreach ($customLinkTranslations as $translation) {
                //Is not valid if each translation is null or eq to empty string
                /** @psalm-suppress PossiblyNullArgument */
                if (null === $translation->getCustomLink() || trim($translation->getCustomLink()) === '') {
                    //Build custom violation on the target field
                    /** @psalm-suppress PossiblyNullArgument */
                    $this->context->buildViolation($constraint->messageLinkNotBlank)
                        ->atPath(sprintf('translations[%s].customLink', $translation->getLocale()))
                        ->addViolation()
                    ;
                }
            }
        }
    }

    protected function buildViolationOnResourceLink(string $message): void
    {
        /**
         * @phpstan-ignore-next-line
         *
         * @psalm-suppress PossiblyNullArgument
         */
        $this->context->buildViolation($message)
            ->atPath('linkIsDefined')
            ->addViolation()
        ;
    }
}
