<?php

use App\Extensions\{AssetRender, Containers, Corrector};
use Cli\Commands\HelloWorldCommand;
use Cli\Console\SymfonyConsole;
use Framework\Emitters\SapiEmitter;
use Framework\Handlers\ErrorResponseHandler;
use Framework\Routers\LeagueRouter;
use Framework\Strategies\ApplicationStrategy;
use App\Templates\Plates;
use League\Container\Definition\{Definition, DefinitionAggregate};

return new DefinitionAggregate([
    new Definition('dependencies', [
        'strategy' => ApplicationStrategy::class,
        'template' => Plates::class,
        'console' => SymfonyConsole::class,
        'handler' => ErrorResponseHandler::class,
        'emitter' => SapiEmitter::class,
        'router' => LeagueRouter::class
    ]),
    new Definition('template', [getenv('TEMPLATE_DIR') ?: 'template', 'php', [
        new AssetRender('public'),
        new Containers,
        new Corrector
    ]]),
    new Definition('commands', [
        HelloWorldCommand::class
    ]),
    new Definition('statics', [getenv('STATICS_DIR') ?: 'statics', [
        'title' => 'Microbe Framework',
        'description' => 'Лёгкий, гибкий и быстрый компонентный PHP 8.3 фреймворк.',
        'keywords' => 'microbe, php, framework, psr',
        'robots' => 'index, follow',
        'body' => '',
    ]])
]);