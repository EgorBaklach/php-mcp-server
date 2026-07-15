<?php

namespace App\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class Containers implements ExtensionInterface
{
    use Traits\Dom;

    public const headings = ['h1', 'h2', 'h3', 'h4', 'h5'];

    public function register(Engine $engine): void
    {
        foreach (self::headings as $heading) {
            $engine->registerFunction($heading, [$this, $heading]);
        }

        $engine->registerFunction('container', __CLASS__.'::container');
        $engine->registerFunction('loner', __CLASS__.'::loner');
    }

    public function __call(string $name, array $arguments): ?string
    {
        if (!in_array($name, self::headings, true)) {
            return null;
        }
        array_unshift($arguments, $name);
        return self::container(...$arguments);
    }
}
