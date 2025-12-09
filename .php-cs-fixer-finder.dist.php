<?php

return PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/bundles',
        __DIR__ . '/config',
        __DIR__ . '/lib',
        __DIR__ . '/models',
        __DIR__ . '/tests'
    ])
    ->exclude([
        __DIR__ . '/tests/_output',
        __DIR__ . '/tests/Support/_generated',
    ]);