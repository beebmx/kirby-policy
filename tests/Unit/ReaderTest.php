<?php

use Beebmx\KirbyPolicy\Reader;
use Kirby\Toolkit\Collection;
use Kirby\Toolkit\Str;

beforeEach(function () {
    $this->reader = new Reader(App());
    $this->reader->exclude('users');
});

it('returns an array of blueprints', function () {
    expect($this->reader->get())
        ->toBeArray();
});

it('returns all the policy files', function () {
    expect($this->reader->exclude([])->get())
        ->toHaveCount(5);
});

it('wont return any excluded file', function () {
    $users = (new Collection($this->reader->get()))
        ->filter(fn ($file) => Str::startsWith($file, 'users'));

    expect($users)
        ->toHaveCount(0);
});

it('returns only the policy available files', function () {
    expect($this->reader->get())
        ->toHaveCount(4);
});
