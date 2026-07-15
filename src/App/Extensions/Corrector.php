<?php

namespace App\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class Corrector implements ExtensionInterface
{
    public function register(Engine $engine): void
    {
        $engine->registerFunction('num2word', [$this, 'num2word']);
        $engine->registerFunction('mb_ucfirst', [$this, 'mb_ucfirst']);
        $engine->registerFunction('numberFormat', [$this, 'numberFormat']);
        $engine->registerFunction('cyrmonth', [$this, 'cyrmonth']);
    }

    public function num2word(int $number, array $after): string
    {
        return $after[($number % 100 > 4 && $number % 100 < 20) ? 2 : [2, 0, 1, 1, 1, 2][min($number % 10, 5)]];
    }

    public function mb_ucfirst(string $str, string $charset = ''): string
    {
        if (empty($charset)) {
            $charset = mb_internal_encoding();
        }

        $letter = mb_strtoupper(mb_substr($str, 0, 1, $charset), $charset);
        $suffix = mb_substr($str, 1, mb_strlen($str, $charset) - 1, $charset);

        return $letter . $suffix;
    }

    public function numberFormat(float|int $money, bool $isInt = false, string $delimiter = '&nbsp;'): string
    {
        return number_format((float)$money, $isInt ? 0 : 2, '.', $delimiter);
    }

    public function cyrmonth(string $date): string
    {
        $months = [
            'ru' => ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'],
            'en' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
        ];

        return str_replace($months['en'], $months['ru'], $date);
    }
}
