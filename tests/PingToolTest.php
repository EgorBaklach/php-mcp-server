<?php namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Tools\PingTool;
use PHPUnit\Framework\Attributes\TestDox;

class PingToolTest extends TestCase
{
    #[TestDox('Проверяет, что PingTool корректно возвращает pong с переданным сообщением или дефолтным hello')]
    public function testPingReturnsExpectedPong(): void
    {
        $tool = new PingTool();
        $this->assertEquals('pong: hello', $tool->ping());
        $this->assertEquals('pong: custom', $tool->ping('custom'));
    }
}
