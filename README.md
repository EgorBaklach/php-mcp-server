# Microbe Framework

**Microbe** — это легкий, высокопроизводительный компонентный PHP-фреймворк, построенный для **PHP 8.3**. Он строго следует современным стандартам PSR, поддерживает внедрение зависимостей через автосвязывание (auto-wiring), а также имеет чистую архитектуру CLI-команд и шаблонизации.

---

## 🚀 Основные возможности

* **PHP 8.3 Ready**: Полная поддержка строгой типизации, Constructor Property Promotion, readonly-свойств и атрибутов.
* **Конфигурация через окружение**: Настройка параметров с помощью `.env` файлов на базе `symfony/dotenv`.
* **PSR-сомместимость**:
  * **PSR-7 & PSR-17**: HTTP-сообщения и фабрики на базе `laminas/laminas-diactoros`.
  * **PSR-11**: Контейнер зависимостей на базе `league/container` v4.
  * **PSR-15**: Стандартная цепочка посредников (Middleware) для обработки запросов.
* **Гибкая маршрутизация**: Быстрый роутер и стратегии обработки на базе `league/route` v5.
* **Шаблонизатор Plates**: Быстрый нативный PHP-шаблонизатор на базе `league/plates` v3.5.
* **Symfony Console 7.x**: Консольный интерфейс с поддержкой автозагрузки команд через атрибуты.
* **Поддержка Unit-тестов**: Готовая интеграция с тестовой средой `phpunit/phpunit` 11.x.

---

## 📁 Структура проекта

```text
├── bin/
│   └── console.php          # Точка входа для CLI (Symfony Console)
├── bootstrap/
│   └── machine.php          # Сборка контейнера зависимостей и загрузка Dotenv
├── config/
│   ├── definitions.php      # DI-определения и настройки сервисов
│   ├── inflectors.php       # Список инфлекторов для автонастройки
│   └── providers.php        # Список регистрируемых сервис-провайдеров
├── public/
│   ├── index.php            # Точка входа для веб-запросов (Front Controller)
│   └── assets/              # Публичные статические файлы (CSS, JS)
├── routes/
│   └── web.php              # Описание маршрутов приложения
├── statics/
│   ├── index.php            # Переопределения статических данных главной страницы
│   └── [404|405|500].php    # Статические данные для страниц ошибок
├── src/
│   ├── App/                 # Исходный код приложения (Контроллеры, Шаблонизаторы)
│   ├── Cli/                 # Исходный код CLI-команд
│   ├── Framework/           # ⚙️ Ядро фреймворка (НЕ ИЗМЕНЯТЬ)
│   └── Magistrale/          # 🧱 Слой расширений (БД, кеш, сессии, фабрики)
├── template/
│   ├── common.php           # Единый макет (layout) для всех страниц
│   ├── index.php            # Шаблон тела главной страницы
│   └── [404|405|500].php    # Шаблоны страниц ошибок
├── tests/
│   ├── ApplicationTest.php  # Интеграционные тесты сборки DI-контейнера
│   └── StaticFactoryTest.php # Юнит-тесты фабрики StaticFactory
├── .backup/                 # Временное резервное копирование чувствительных данных (содержимое игнорируется git)
├── .env.example             # Шаблон файла переменных окружения
└── phpunit.xml              # Конфигурация тестов PHPUnit
```

---

## 🛠️ Быстрый старт

### 1. Подготовка
Создайте локальный файл конфигурации `.env` из шаблона:
```bash
cp .env.example .env
```

### 2. Запуск в Docker
Запустите легковесное PHP-окружение в фоновом режиме (веб-сервер запустится на порту `9000`):
```bash
docker compose up -d --build
```
Откройте в браузере: http://localhost:9000/

### 3. Запуск CLI команд
```bash
docker exec microbe php bin/console.php hello:world
```

### 4. Запуск тестов
```bash
docker exec microbe vendor/bin/phpunit
```

---

## 🔧 Архитектура фреймворка

### 1. Ядро vs Расширения: Framework и Magistrale

Фреймворк строго разделяет неизменяемое системное ядро от прикладных инструментов проекта:
* **`src/Framework/` (Неприкосновенное ядро)**: Отвечает за маршрутизацию, базовую конфигурацию DI, эмиттеры ответов и общие стратегии. Этот код не должен содержать бизнес-логику проекта или зависимости от Magistrale.
* **`src/Magistrale/` (Расширения проекта)**: Место для интеграционных классов проекта (БД, кеш, сессии, фабрики данных). Например, `StaticFactory` читает статические файлы из `statics/` и накладывает их на базовые параметры.
* **`src/App/Templates/Plates.php` (Интеграционный мост)**: Создан на уровне приложения и наследует базовый класс Plates из ядра. Он перехватывает вызовы рендеринга, внедряет фабрику `StaticFactory` для автоматического извлечения статических данных страниц и прозрачно рендерит общий лейаут `template/common.php`.

### 2. Пример контроллера
Благодаря интеграционному мосту, контроллеры не содержат логики склеивания макетов или статических мета-данных:
```php
<?php namespace App\Controllers;

use Framework\Contracts\Template\TemplateInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;

class Home
{
    public function __construct(private readonly TemplateInterface $template) {}

    public function __invoke(): ResponseInterface
    {
        // Рендерит шаблон index. Вся склейка с макетом и мета-данными скрыта в App\Templates\Plates
        return new HtmlResponse($this->template->render('index'));
    }
}
```

### 3. Пример консольной команды (CLI)
Команды наследуют `Symfony\Component\Console\Command\Command` and реализуют `Framework\Contracts\Console\CommandInterface`.
Для оптимизации памяти запрещено внедрять тяжелые зависимости в `__construct()`. Используйте ленивый метод `construct()`:
```php
<?php namespace Cli\Commands;

use Framework\Contracts\Console\CommandInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'app:my-command', description: 'Описание моей команды')]
class MyCommand extends Command implements CommandInterface
{
    private ContainerInterface $container;
    private MyService $service;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function construct(): void
    {
        // Зависимости разрешаются только тогда, когда команда запущена
        $this->service = $this->container->get(MyService::class);
    }

    protected function execute($input, $output): int
    {
        $this->service->run();
        return Command::SUCCESS;
    }
}
```
