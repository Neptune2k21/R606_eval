<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/src/templates',
    ])
    ->append([
        __DIR__ . '/index.php',
        __DIR__ . '/config.php',
    ]);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12'                     => true,
        '@PHP82Migration'            => true,
        'strict_param'               => true,
        'declare_strict_types'       => true,
        'array_syntax'               => ['syntax' => 'short'],
        'no_unused_imports'          => true,
        'ordered_imports'            => ['sort_algorithm' => 'alpha'],
        'single_quote'              => true,
        'trailing_comma_in_multiline' => true,
        'no_whitespace_in_blank_line' => true,
    ])
    ->setFinder($finder);