<?php namespace Magistrale\Factories;

use League\Container\DefinitionContainerInterface;

class StaticFactory implements FactoryInterface
{
    public function __construct(private DefinitionContainerInterface $container){}

    public function get(string $name): array
    {
        if(!$this->container->has($name))
        {
            $this->container->add($name, new class($name, ...$this->container->get('statics')) implements StaticInterface
            {
                private array $data;

                public function __construct(string $name, string $directory, private array $default)
                {
                    $file = $directory.DIRECTORY_SEPARATOR.$name.'.php'; $this->data = file_exists($file) ? (require $file) : [];
                }

                public function data(): array
                {
                    return $this->data + $this->default;
                }
            });
        }

        return $this->container->get($name)->data();
    }
}
