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

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Product\IndexPageInterface;
use Sylius\Component\Core\Model\TaxonInterface;

class TaxonContext implements Context
{
    /** @var IndexPageInterface */
    protected $indexPage;

    public function __construct(IndexPageInterface $indexPage)
    {
        $this->indexPage = $indexPage;
    }

    /**
     * @Then /^I should be on ("[^"]+" taxon) page$/
     */
    public function iShouldBeOnTaxonPage(TaxonInterface $taxon)
    {
        $this->indexPage->verify(['slug' => $taxon->getSlug()]);
    }
}
