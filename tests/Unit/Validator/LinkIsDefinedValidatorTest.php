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

namespace Tests\Wemea\SyliusMenuPlugin\Unit\PluginUnitTest\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use Wemea\SyliusMenuPlugin\Entity\MenuItem;
use Wemea\SyliusMenuPlugin\Entity\MenuLink;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkTranslation;
use Wemea\SyliusMenuPlugin\Entity\MenuLinkTranslationInterface;
use Wemea\SyliusMenuPlugin\Model\MenuLinkInterface;
use Wemea\SyliusMenuPlugin\Validator\Constraints\LinkIsDefined;
use Wemea\SyliusMenuPlugin\Validator\Constraints\LinkIsDefinedValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\Taxon;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class LinkIsDefinedValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function itValidatesMenuLinkInterface(): void
    {
        //create another object different to MenuLink
        $badObjectParameter = new MenuItem();
        $constraint = new LinkIsDefined();
        $this->expectException(UnexpectedValueException::class);
        $validator = new LinkIsDefinedValidator();
        $validator->validate($badObjectParameter, $constraint);
    }

    /**
     * @test
     */
    public function itUsesLinkIsDefinedConstraint(): void
    {
        $menuLink = new MenuLink();
        $badConstraint = new NotBlank();
        $this->expectException(UnexpectedTypeException::class);
        $validator = new LinkIsDefinedValidator();
        $validator->validate($menuLink, $badConstraint);
    }

    /**
     * @test
     * @dataProvider validateMenuLinkSuccessDataProvider
     */
    public function validateSuccess(MenuLinkInterface $value): void
    {
        //prepare variables
        $context = $this->getMockValidatorContext(false);
        $constraint = new LinkIsDefined();
        $validator = new LinkIsDefinedValidator();

        //do assert
        $validator->initialize($context);
        $validator->validate($value, $constraint);
    }

    /**
     * @test
     * @dataProvider validateMenuLinkFailedDataProvider
     */
    public function validateFailed(MenuLinkInterface $value): void
    {
        //prepare variables
        $context = $this->getMockValidatorContext(true);
        $constraint = new LinkIsDefined();
        $validator = new LinkIsDefinedValidator();

        //do assert
        $validator->initialize($context);
        $validator->validate($value, $constraint);
    }

    public function validateMenuLinkSuccessDataProvider(): array
    {
        $data = [];

        //validate with product target
        $data[] = [
            $this->createMenuLink('product', new Product()),
        ];

        //validate with taxon target
        $data[] = [
            $this->createMenuLink('taxon', new Taxon()),
        ];

        //validate with one path
        $translation = $this->getMenuLinkTranslation('en_US', '/my/link');
        $data[] = [
            $this->createMenuLink('translations', new ArrayCollection([$translation])),
        ];

        //validate with multiple translation
        $data[] = [
            $this->createMenuLink('translations', new ArrayCollection([
                $this->getMenuLinkTranslation('en_US', '/my/link'),
                $this->getMenuLinkTranslation('fr_FR', '/mon/lien'),
            ])),
        ];

        return $data;
    }

    public function validateMenuLinkFailedDataProvider(): array
    {
        $data = [];

        //failed with null product
        $data[] = [
            $this->createMenuLink('product', null),
        ];

        //failed with null taxon
        $data[] = [
            $this->createMenuLink('taxon', null),
        ];

        //failed with null translations
        $data[] = [
            $this->createMenuLink('translations', null),
        ];

        //failed with empty collection
        $data[] = [
            $this->createMenuLink('translations', new ArrayCollection()),
        ];

        //failed with with custom link undefined on all locale
        $data[] = [
            $this->createMenuLink('translations', new ArrayCollection([
                $this->getMenuLinkTranslation('en_US', null),
            ])),
        ];

        //failed with with blank custom link
        $data[] = [
            $this->createMenuLink('translations', new ArrayCollection([
                $this->getMenuLinkTranslation('en_US', '   '),
            ])),
        ];

        //failed with with 1 of 2 null custom link
        $data[] = [
            $this->createMenuLink('translations', new ArrayCollection([
                $this->getMenuLinkTranslation('en_US', '/my/link'),
                $this->getMenuLinkTranslation('fr', null),
            ])),
        ];

        return $data;
    }

    /**
     * @return MockObject|ExecutionContextInterface
     */
    protected function getMockValidatorContext(bool $expectViolation): object
    {
        $mock = $this->getMockBuilder(ExecutionContextInterface::class)
            ->getMock();

        if ($expectViolation) {
            $violation = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)
                ->disableOriginalConstructor()
                ->getMock();
            $violation->method('atPath')->willReturn($violation);
            $violation->expects($this->once())->method('addViolation');
            $mock
               ->expects($this->once())
               ->method('buildViolation')
               ->willReturn($violation)
           ;
        } else {
            $mock
                ->expects($this->never())
                ->method('buildViolation');
        }

        return $mock;
    }

    protected function createMenuLink(string $type, $value): MenuLinkInterface
    {
        $menuLink = new MenuLink();
        $menuLink->setLinkResource($type, $value);

        return $menuLink;
    }

    protected function getMenuLinkTranslation(string $locale, ?string $customLink): MenuLinkTranslationInterface
    {
        $translation = new MenuLinkTranslation();
        $translation->setLocale($locale);
        $translation->setCustomLink($customLink);

        return $translation;
    }
}
