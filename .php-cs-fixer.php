<?php

// Include tests and support functions.
$finder = PhpCsFixer\Finder::create()
  ->exclude('_output')
  ->exclude('_support')
  ->exclude('Support/_generated')
  ->in(__DIR__ . '/src')
;

// Adjust the list of rules here.
$config = new PhpCsFixer\Config();
return $config->setRules([
  '@PSR12' => true,
  'array_syntax' => ['syntax' => 'short'],
])->setFinder($finder);
