<?php namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Tools\CalculateTool;
use PHPUnit\Framework\Attributes\TestDox;

class CalculateToolTest extends TestCase
{
    private CalculateTool $tool;

    protected function setUp(): void
    {
        $this->tool = new CalculateTool();
    }

    #[TestDox('Проверяет корректное вычисление математических выражений')]
    public function testCalculateValidExpressions(): void
    {
        $this->assertEquals('15', $this->tool->calculate('(10+5)'));
        $this->assertEquals('45', $this->tool->calculate('(10+5)*3'));
        $this->assertEquals('2.5', $this->tool->calculate('5/2'));
    }

    #[TestDox('Проверяет, что недопустимые символы в выражении приводят к выбросу исключения')]
    public function testCalculateInvalidCharactersThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->tool->calculate('2+2; system("ls")');
    }
}
