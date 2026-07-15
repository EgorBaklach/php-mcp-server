<?php

use Cli\Providers\ServiceProvider as CliServiceProvider;
use Magistrale\Providers\AppProvider;
use Framework\Providers\ProviderAggregate;
use Framework\Providers\ServiceProvider;

return new ProviderAggregate([
    CliServiceProvider::class,
    ServiceProvider::class,
    AppProvider::class
]);