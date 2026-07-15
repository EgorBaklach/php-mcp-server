<?php namespace Framework\Middlewares;

use Framework\Handlers\ErrorHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class ThrowableMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ErrorHandlerInterface $handler){}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try
        {
            return $handler->handle($request);
        }
        catch (Throwable $error)
        {
            return $this->handler->handle($request, $error);
        }
    }
}
