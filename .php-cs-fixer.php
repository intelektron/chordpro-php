<?php

// Include tests and support functions.
$finder = PhpCsFixer\Finder::create()
  ->in(__DIR__ . '/src')
  ->in(__DIR__ . '/tests')
;

// Adjust the list of rules here.
$config = new PhpCsFixer\Config();
return $config->setRules([
  '@PSR12' => true,
  'array_syntax' => ['syntax' => 'short'],
])->setFinder($finder);
