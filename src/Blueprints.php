<?php

namespace Beebmx\KirbyPolicy;

use ArgumentCountError;
use Kirby\Cms\App as Kirby;
use Kirby\Cms\Role;
use Kirby\Cms\User;
use Kirby\Data\Data;
use Kirby\Toolkit\Collection;

class Blueprints
{
    protected Collection $files;

    protected string $base;

    protected Role $role;

    public function __construct(protected Kirby $kirby)
    {
        $this->base = $this->kirby->roots()->blueprints();

        $this->files = new Collection(
            (new Reader($this->kirby))
                ->exclude(
                    $this->kirby->option('beebmx.kirby-policy.excluded', 'users')
                )->get()
        );
    }

    public function load(): static
    {
        $this->files->map(
            fn ($file) => Data::read("{$this->base}/{$file}")
        );

        return $this;
    }

    public function withRole(Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function files(): array
    {
        return $this->files->toArray();
    }

    public function content(): array
    {
        return $this->files
            ->map(fn ($file) => static::map($file,
                fn ($content, $key) => $this->parse($content, $key)
            ))->map(fn ($file) => $this->sanitized($file))->toArray();
    }

    public function parse($content, $key): array|string|null
    {
        return match (true) {
            is_array($content) && array_key_exists('policy', $content) && $this->can($content['policy']) => $content,
            is_array($content) && array_key_exists('policy', $content) && ! $this->can($content['policy']) => null,
            is_array($content) && $key !== 'policy' => static::map($content, fn ($c, $k) => $this->parse($c, $k)),
            default => $content,
        };
    }

    public function sanitized(array $content): array
    {
        foreach ($content as $key => &$value) {
            if (is_array($value)) {
                $value = $this->sanitized($value);
                if (empty($value)) {
                    unset($content[$key]);
                }
                if (array_key_exists('policy', $value)) {
                    unset($value['policy']);
                }
            } elseif ($content[$key] === null) {
                unset($content[$key]);
            }
        }

        return $content;
    }

    public function can(string|array $policy): bool
    {
        return $policy === $this->role->name() || is_array($policy) && in_array($this->role->name(), $policy);
    }

    public static function filter($array, callable $callback): array
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    public static function map(array $array, callable $callback): array
    {
        $keys = array_keys($array);

        try {
            $items = array_map($callback, $array, $keys);
        } catch (ArgumentCountError) {
            $items = array_map($callback, $array);
        }

        return array_combine($keys, $items);
    }

    public static function for(?User $user, ?Kirby $kirby = null): array
    {
        $app = $kirby ?? kirby();

        if (empty($user)) {
            return [];
        }

        if (! (new Profiles($app))->get()->has($user->role()->name())) {
            return [];
        }

        return (new static($app))
            ->load()
            ->withRole($user->role())
            ->content();
    }
}
