<?php namespace App\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\Asset;
use League\Plates\Extension\ExtensionInterface;

class AssetRender implements ExtensionInterface
{
    use Traits\Dom;

    private array $assets = [];
    private readonly Asset $asset;

    public function __construct(string $path = 'public')
    {
        $this->asset = new Asset($path);
    }

    public function register(Engine $engine): void
    {
        $engine->registerFunction('group', [$this, 'group']);
        $engine->registerFunction('get', [$this, 'get']);
    }

    public function get(string $url): string
    {
        if (!array_key_exists($url, $this->assets)) {
            $this->add($url);
        }
        return $this->assets[$url];
    }

    private function add(string $url): void
    {
        $this->assets[$url] = ([$this, pathinfo($url, PATHINFO_EXTENSION)])($this->asset->cachedAssetUrl($url));
    }

    protected function js(string $url): string
    {
        return self::container('script', false, ['type' => 'text/javascript', 'src' => $url]);
    }

    protected function css(string $url): string
    {
        return self::loner('link', ['type' => 'text/css', 'rel' => 'stylesheet', 'href' => $url]);
    }

    public function group(array $urls): string
    {
        $new = [];
        foreach ($urls as $url) {
            $new[] = $this->get($url);
        }
        return implode(PHP_EOL, $new);
    }
}