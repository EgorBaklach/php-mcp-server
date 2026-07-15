<?php namespace Magistrale\Providers;

use Framework\Contracts\Template\TemplateInterface;
use Framework\Providers\ProviderAbstract;
use League\Container\DefinitionContainerInterface;

class AppProvider extends ProviderAbstract
{
    protected array $provides = [
        DefinitionContainerInterface::class
    ];

    public function register(): void
    {
        $this->container()->add(DefinitionContainerInterface::class, $this->container());
    }
}