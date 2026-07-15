<?php

use App\Strategies\McpJsonStrategy;
use App\Tools\CalculateTool;
use App\Tools\PingTool;
use Cli\Commands\HelloWorldCommand;
use Cli\Console\SymfonyConsole;
use Framework\Emitters\SapiEmitter;
use Framework\Routers\LeagueRouter;
use League\Container\Definition\{Definition, DefinitionAggregate};

return new DefinitionAggregate([
    new Definition('dependencies', [
        'strategy' => McpJsonStrategy::class,
        'console'  => SymfonyConsole::class,
        'emitter'  => SapiEmitter::class,
        'router'   => LeagueRouter::class
    ]),
    new Definition('commands', [
        HelloWorldCommand::class
    ]),
    new Definition('mcp.settings', [
        'server_name' => 'mcp-server',
        'server_ver'  => '1.0.0',
        'session_dir' => sys_get_temp_dir() . '/mcp-sessions',
        'logging'     => [
            'stream' => 'php://stderr',
        ]
    ]),
    new Definition('mcp.tools', [
        [
            'handler'     => [PingTool::class, 'ping'],
            'name'        => 'ping',
            'description' => 'Returns "pong: {message}". Useful for connectivity tests.'
        ],
        [
            'handler'     => [CalculateTool::class, 'calculate'],
            'name'        => 'calculate',
            'description' => 'Evaluates a simple arithmetic expression, e.g. "(10+5)*3".'
        ]
    ])
]);