<?php

namespace Beebmx\KirbyPolicy;

use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\Dir;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Collection;
use Kirby\Toolkit\Str;

class Reader
{
    protected array $excluded = [];

    protected string $suffix;

    public function __construct(protected Kirby $kirby)
    {
        $this->suffix = $this->kirby->option('beebmx.kirby-policy.suffix', 'policy');
    }

    public function exclude(string|array $excluded): self
    {
        if (is_string($excluded)) {
            $this->excluded[] = $this->kirby->roots()->blueprints().'/'.$excluded;
        } else {
            $this->excluded = $excluded;
        }

        return $this;
    }

    public function get(): array
    {
        $collection = (new Collection(Dir::index($this->kirby->roots()->blueprints(), true, $this->excluded)))
            ->filter(
                fn (string $file) => Str::endsWith($file, "{$this->suffix}.yml") || Str::endsWith($file, "{$this->suffix}.yaml")
            )->map(function ($file) {
                $blueprint = str_replace(".{$this->suffix}.yaml", '', str_replace(".{$this->suffix}.yml", '', $file));

                return ['blueprint' => $blueprint, 'file' => $file];
            });

        return A::map((A::keyBy($collection->toArray(), 'blueprint')), fn ($item) => $item['file']);
    }
}
