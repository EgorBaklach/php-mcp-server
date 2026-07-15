<?php namespace Tests;

use PHPUnit\Framework\TestCase;
use League\Container\Container;
use League\Container\Definition\Definition;
use League\Container\Definition\DefinitionAggregate;
use Magistrale\Factories\StaticFactory;

class StaticFactoryTest extends TestCase
{
    private Container $container;
    private StaticFactory $factory;

    protected function setUp(): void
    {
        $this->container = new Container();
        
        // Регистрируем определение statics (имитация config/definitions.php)
        $this->container->add('statics', [
            'statics', // директория
            [
                'title' => 'Default Title',
                'description' => 'Default Description',
                'robots' => 'index, follow',
                'body' => ''
            ]
        ]);

        $this->factory = new StaticFactory($this->container);
    }

    public function testGetReturnsMergedDataWithDefaults(): void
    {
        // Вызываем получение статики для index (которая реально лежит в /statics/index.php)
        $data = $this->factory->get('index');

        // Проверяем, что вернулся массив
        $this->assertIsArray($data);

        // Проверяем, что переопределенный body наложился поверх дефолтного
        $this->assertEquals('Hello World', $data['body']);

        // Проверяем, что унаследованные из DI definitions ключи на месте
        $this->assertEquals('Default Title', $data['title']);
        $this->assertEquals('Default Description', $data['description']);
        $this->assertEquals('index, follow', $data['robots']);
    }

    public function testGetNonExistentPageReturnsOnlyDefaults(): void
    {
        // Запрашиваем несуществующую статику
        $data = $this->factory->get('non_existent_page_meta_data_test');

        $this->assertIsArray($data);
        $this->assertEquals('Default Title', $data['title']);
        $this->assertEquals('', $data['body']);
    }
}
