<?php

use Beebmx\KirbyPolicy\Profiles;

beforeEach(function () {
    $this->profiles = new Profiles(App());
});

it('wont return a role with suffix', function () {
    expect($this->profiles->get()->has('demo.policy'))
        ->toBeFalse();
});

it('wont return a role without panel access', function () {
    expect($this->profiles->get()->has('guest'))
        ->toBeFalse();
});

it('returns all the roles with access panel', function () {
    expect($this->profiles->get())
        ->toHaveCount(2);
});
