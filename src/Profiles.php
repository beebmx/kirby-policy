<?php

namespace Beebmx\KirbyPolicy;

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Role;
use Kirby\Cms\Roles;
use Kirby\Toolkit\Collection;
use Kirby\Toolkit\Str;

class Profiles
{
    protected Roles $roles;

    protected string $suffix;

    public function __construct(protected Kirby $kirby)
    {
        $this->suffix = $this->kirby->option('beebmx.kirby-policy.suffix', 'policy');

        $this->roles = Roles::load($this->kirby->roots()->roles());
    }

    public function get(): Roles|Collection
    {
        return $this->roles
            ->filter(
                fn (Role $role) => ! Str::endsWith($role->name(), $this->suffix)
            )->filter(
                fn (Role $role) => $role->permissions()->for('access', 'panel')
            );
    }
}
