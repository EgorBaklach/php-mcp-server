<?php namespace Framework\Handlers;

use Framework\Contracts\Template\TemplateInterface;
use League\Container\Container;
use League\Route\Http\Exception\HttpExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class ErrorResponseHandler implements ErrorHandlerInterface
{
    private readonly ResponseFactoryInterface $factory;

    private readonly TemplateInterface $engine;

    public function __construct(ContainerInterface $container)
    {
        $this->factory = $container->get(ResponseFactoryInterface::class);
        $this->engine = $container->get(TemplateInterface::class);
    }

    public function handle(ServerRequestInterface $request, Throwable $error): ResponseInterface
    {
        $code = $error instanceof HttpExceptionInterface ? $error->getStatusCode() : $error->getCode(); $code = in_array($code, [404, 405], true) ? $code : 500;

        $response = $this->factory->createResponse($code); $response->getBody()->write($this->engine->render($code, ['error' => $error]));

        return $response->withStatus($code, strtok($error->getMessage(), "\n"));
    }
}