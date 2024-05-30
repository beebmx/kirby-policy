<?php

use Beebmx\KirbyPolicy\Blueprints;
use Kirby\Cms\App as Kirby;

@include_once __DIR__.'/vendor/autoload.php';

Kirby::plugin('beebmx/kirby-policy', [
    'options' => [
        'excluded' => 'users',
        'suffix' => 'policy',
    ],

    'blueprints' => Blueprints::for(kirby()->user()),
]);
