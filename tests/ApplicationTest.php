<?php namespace Tests;

use PHPUnit\Framework\TestCase;
use Framework\Application;
use Framework\Contracts\Template\TemplateInterface;
use Framework\Contracts\Router\RouterInterface;
use Framework\Contracts\Emitter\EmitterInterface;
use Framework\Handlers\ErrorHandlerInterface;
use Magistrale\Factories\StaticFactory;

class ApplicationTest extends TestCase
{
    private array $config;

    protected function setUp(): void
    {
        // Загружаем реальную конфигурацию сборки машины фреймворка
        $this->config = require __DIR__ . '/../bootstrap/machine.php';
    }

    public function testApplicationContainerBootstrap(): void
    {
        $app = Application::make($this->config);
        $this->assertInstanceOf(Application::class, $app);
    }

    public function testRequiredServicesAreAvailableInContainer(): void
    {
        // Создаем инстанс приложения
        $app = Application::make($this->config);
        
        // Получаем DI-контейнер через рефлексию (так как свойство protected/private)
        $reflector = new \ReflectionClass($app);
        $containerProperty = $reflector->getProperty('container');
        $containerProperty->setAccessible(true);
        $container = $containerProperty->getValue($app);

        // Проверяем наличие ключевых интерфейсов в контейнере
        $this->assertTrue($container->has(TemplateInterface::class));
        $this->assertTrue($container->has(RouterInterface::class));
        $this->assertTrue($container->has(EmitterInterface::class));
        $this->assertTrue($container->has(ErrorHandlerInterface::class));

        // Проверяем успешное разрешение (resolve) каждого сервиса
        $this->assertInstanceOf(TemplateInterface::class, $container->get(TemplateInterface::class));
        $this->assertInstanceOf(RouterInterface::class, $container->get(RouterInterface::class));
        $this->assertInstanceOf(StaticFactory::class, $container->get(StaticFactory::class));

        // Проверяем успешное создание каждой зарегистрированной команды
        $commands = $container->get('commands');
        $this->assertIsArray($commands);
        foreach ($commands as $commandClass) {
            $command = $container->get($commandClass);
            $this->assertInstanceOf(\Symfony\Component\Console\Command\Command::class, $command);
        }
    }
}
