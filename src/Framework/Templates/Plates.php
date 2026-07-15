<?php namespace Framework\Templates;

use Framework\Contracts\Template\TemplateInterface;
use League\Plates\Engine;
use Psr\Container\ContainerInterface;

class Plates implements TemplateInterface
{
    protected readonly Engine $engine;

    public function __construct(protected ContainerInterface $container)
    {
        [$path, $extension, $extensions] = $this->container->get('template'); $this->engine = $container->get(Engine::class);

        $this->engine->setDirectory($path);
        $this->engine->setFileExtension($extension);
        $this->engine->loadExtensions($extensions);
    }

    public function render(string $name, array $params = []): string
    {
        return $this->engine->make($name)->render($params);
    }
}
