<?php namespace App\Templates;

use Framework\Templates\Plates as BasePlates;
use Psr\Container\ContainerInterface;
use Magistrale\Factories\StaticFactory;

class Plates extends BasePlates
{
    private StaticFactory $statics;

    public function __construct(protected ContainerInterface $container)
    {
        $this->statics = $container->get(StaticFactory::class); parent::__construct($container);
    }

    public function render(string $name, array $params = []): string
    {
        return $this->engine->make('common')->render($params + $this->statics->get($name));
    }
}
