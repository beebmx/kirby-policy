<?php

use Beebmx\KirbyPolicy\Blueprints;
use Kirby\Toolkit\A;

beforeEach(function () {
    $this->blueprint = new Blueprints(App());
});

it('returns all the policy files', function () {
    expect($this->blueprint->files())
        ->toHaveCount(4);
});

it('loads an array with the policy', function () {
    $this->blueprint->load();

    expect(A::first($this->blueprint->files()))
        ->toBeArray();
});
