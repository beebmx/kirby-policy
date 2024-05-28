<?php

use Beebmx\KirbyPolicy\Blueprints;
use Kirby\Cms\User;

it('wont return any blueprint for invalid role', function ($role) {
    expect(Blueprints::for(new User(['role' => $role]), App()))
        ->toBeArray()
        ->toHaveCount(0);
})->with([
    ['role' => null],
    ['role' => 'invalid'],
    ['role' => 'guest'],
    ['role' => 'default'],
]);

it('return an array with blueprints for given user', function ($role) {
    expect(Blueprints::for(new User(['role' => $role]), App()))
        ->toBeArray()
        ->toHaveCount(4);
})->with([
    ['role' => 'admin'],
    ['role' => 'editor'],
]);
