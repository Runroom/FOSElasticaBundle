<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$header = <<<EOF
This file is part of the FOSElasticaBundle package.

(c) FriendsOfSymfony <http://friendsofsymfony.github.com/>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

$finder = Finder::create()
    ->in(__DIR__);

$config = new Config();

$config->setParallelConfig(ParallelConfigFactory::detect());
$config->setRules([
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
    'combine_consecutive_unsets' => true,
    'header_comment' => ['header' => $header],
    'linebreak_after_opening_tag' => true,
    'no_php4_constructor' => true,
    'no_useless_else' => true,
    'ordered_class_elements' => true,
    'ordered_imports' => true,
    'php_unit_construct' => true,
    'phpdoc_no_empty_return' => false,
    'trailing_comma_in_multiline' => ['after_heredoc' => true, 'elements' => ['array_destructuring', 'arrays', 'match']],
])
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setFinder($finder);

return $config;
