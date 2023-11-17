<?php

$header = <<<'EOF'
This file is part of Wemea Menu plugin for Sylius.

(c) Wemea (wemea.fr)

For the full copyright and license information, please view the LICENSE.txt
file that was distributed with this source code.
EOF;

$finder = (new PhpCsFixer\Finder())
    ->in(['src', 'spec', 'tests/Behat', 'tests/Integration', 'tests/Unit'])
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        'header_comment' => ['header' => $header],
    ])
    ->setFinder($finder)
;
