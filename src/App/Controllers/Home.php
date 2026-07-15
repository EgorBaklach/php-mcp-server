<?php namespace App\Controllers;

use Framework\Contracts\Template\TemplateInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Home
{
    public function __construct(private readonly TemplateInterface $template){}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->template->render('index'));
    }
}