<?php

use Beebmx\KirbyPolicy\Blueprints;
use Kirby\Cms\User;

it('returns empty array for null user', function () {
    $kirby = App();
    expect(Blueprints::for($kirby->user(), $kirby))
        ->toBeArray()
        ->toBeEmpty();
});

test('pages/admin for role', function (string $role) {
    $pages = Blueprints::for(new User(['role' => $role]), App());

    expect($pages['pages/admin'])
        ->toHaveKey('title', 'Admin page')
        ->when($role === 'admin',
            fn ($page) => $page
                ->toHaveKey('columns.main.sections.fields.fields.private')
                ->toHaveKey('columns.sidebar.sections.pages')
        )->when($role === 'editor',
            fn ($page) => $page
                ->not()->toHaveKey('columns.main.sections.fields.fields.private')
                ->not()->toHaveKey('columns.sidebar.sections.pages')
        );
})->with([['admin'], ['editor']]);

test('pages/editor for role', function (string $role) {
    $pages = Blueprints::for(new User(['role' => $role]), App());

    expect($pages['pages/editor'])
        ->toHaveKey('title', 'Editor page')
        ->when($role === 'editor',
            fn ($page) => $page
                ->toHaveKey('columns.main.sections.fields.fields.private')
                ->toHaveKey('columns.sidebar.sections.pages')
        )->when($role === 'admin',
            fn ($page) => $page
                ->not()->toHaveKey('columns.main.sections.fields.fields.private')
                ->not()->toHaveKey('columns.sidebar.sections.pages')
        );
})->with([['admin'], ['editor']]);

test('pages/demo for role', function (string $role) {
    $pages = Blueprints::for(new User(['role' => $role]), App());

    expect($pages['pages/demo'])
        ->toHaveKey('title', 'Demo page')
        ->when($role === 'admin',
            fn ($page) => $page
                ->toHaveKey('columns.main.sections.fields.fields.admin')
                ->toHaveKey('columns.main.sections.fields.fields.mixed')
                ->not()->toHaveKey('columns.main.sections.fields.fields.editor')
        )->when($role === 'editor',
            fn ($page) => $page
                ->toHaveKey('columns.main.sections.fields.fields.editor')
                ->toHaveKey('columns.main.sections.fields.fields.mixed')
                ->not()->toHaveKey('columns.main.sections.fields.fields.admin')
        );
})->with([['admin'], ['editor']]);

test('pages/complex for role', function (string $role) {
    $pages = Blueprints::for(new User(['role' => $role]), App());

    expect($pages['pages/complex'])
        ->toHaveKey('title', 'Complex page')
        ->when($role === 'admin',
            fn ($page) => $page
                ->toHaveKey('tabs.adminOnly')
                ->toHaveKey('tabs.content.columns.main.sections.content.fields.admin')
                ->toHaveKey('tabs.content.columns.main.sections.content.fields.mixed')
                ->not()->toHaveKey('tabs.content.columns.main.sections.content.fields.editor')
        )->when($role === 'editor',
            fn ($page) => $page
                ->toHaveKey('tabs.content.columns.main.sections.content.fields.editor')
                ->toHaveKey('tabs.content.columns.main.sections.content.fields.mixed')
                ->not()->toHaveKey('adminOnly')
                ->not()->toHaveKey('tabs.content.columns.main.sections.content.admin')
        );
})->with([['admin'], ['editor']]);
