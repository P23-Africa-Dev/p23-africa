<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/routes',
        __DIR__ . '/database',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->name('*.blade.php')
    ->notPath('vendor');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setRules([
        // Laravel-friendly defaults
        '@PSR12' => true,                 // Standard PSR-12 formatting
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'single_space'],
        'blank_line_before_statement' => ['statements' => ['return']],
        'no_unused_imports' => true,
        'no_trailing_whitespace' => true,
        'no_extra_blank_lines' => true,
        'single_quote' => true,
        'concat_space' => ['spacing' => 'one'],
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],

        // Blade support
        'phpdoc_trim' => true,
        'phpdoc_indent' => true,
    ])
    ->setFinder($finder);
