<?php namespace Framework\Contracts\Template;

interface TemplateInterface
{
    public function render(string $name, array $params = []): string;
}
