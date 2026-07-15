<?php namespace Framework\Middlewares;

use Framework\Handlers\ErrorHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class ErrorResponseMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ErrorHandlerInterface $handler, private readonly Throwable $error){}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->handler->handle($request, $this->error);
    }
}
