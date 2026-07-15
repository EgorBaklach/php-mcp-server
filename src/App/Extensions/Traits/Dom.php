<?php namespace App\Extensions\Traits;

trait Dom
{
    public static function loner(string $tag, array $attr = []): string
    {
        return '<' . $tag . self::attributes($attr) . '/>';
    }

    public static function container(string $tag, string|bool $content = '', array $attr = []): string
    {
        return '<' . $tag . self::attributes($attr) . '>' . $content . '</' . $tag . '>';
    }

    private static function attributes(array $attributes): string
    {
        $data = [];
        foreach ($attributes as $name => $value) {
            $data[] = is_int($name) ? $name : $name . '="' . $value . '"';
        }
        return implode(' ', $data);
    }
}