<?php

use Kirby\Cms\App as Kirby;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

pest()->uses(Tests\TestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function App(): Kirby
{
    Kirby::$enableWhoops = false;

    return new Kirby([
        'roots' => [
            'index' => '/dev/null',
            'base' => $base = dirname(__DIR__),
            'test' => $tests = $base.'/tests',
            'blueprints' => $tests.'/blueprints',
        ],
    ]);
}
