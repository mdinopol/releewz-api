<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRules([
        '@PSR2'                           => true,
        '@Symfony'                        => true,
        '@PHP81Migration'                 => true,
        'class_attributes_separation'     => ['elements' => ['const' => 'none', 'method' => 'one', 'property' => 'one', 'trait_import' => 'none', 'case' => 'none']],
        'no_superfluous_phpdoc_tags'      => false,
        'compact_nullable_typehint'       => true,
        'list_syntax'                     => ['syntax' => 'short'],
        'no_null_property_initialization' => true,
        'no_superfluous_elseif'           => true,
        'no_useless_else'                 => true,
        'ordered_class_elements'          => true,
        'simplified_null_return'          => true,
        'yoda_style'                      => false,
        'single_line_throw'               => false,
        'binary_operator_spaces'          => [
            'operators' => [
                '='  => 'align_single_space_minimal',
                '=>' => 'align_single_space_minimal',
            ],
        ],
    ])
    ->setFinder(
        Finder::create()
            ->in(__DIR__)
            ->path([
                'app',
                'config',
                'database',
                'routes',
                'tests',
            ])
            ->notPath('application/views')
            ->name('*.php')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    );
