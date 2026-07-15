<?php namespace Magistrale\Factories;

interface FactoryInterface
{
    public function get(string $name): mixed;
}