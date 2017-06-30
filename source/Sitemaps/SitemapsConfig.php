<?php

namespace Spiral\Sitemaps;

use Spiral\Core\InjectableConfig;
use Spiral\Sitemaps\Exceptions\UnknownSitemapException;

class SitemapsConfig extends InjectableConfig
{
    const CONFIG = 'modules/sitemaps';

    protected $config = [
        'sitemaps'         => [
            'itemsLimit'   => 50000,
            //49.59mb actually - a little bit smaller than 50mb, enough to write closing tag
            'sizeLimit'    => 52000000,
            'compression'  => true,
            'subDirectory' => 'sitemaps/',
        ],
        'namespaceAliases' => [
            'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'           => ['default'],
            'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' => ['image', 'images'],
            'xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"' => ['video', 'videos'],
            'xmlns:xhtml="http://www.w3.org/1999/xhtml"'                    => [
                'alterLang',
                'alterLangs',
                'lang',
                'langs'
            ]
        ]
    ];

    /**
     * Get namespace by short alias provided.
     *
     * @param string $alias
     *
     * @return string
     */
    public function getNamespace(string $alias): string
    {
        foreach ($this->config['namespaceAliases'] as $namespace => $aliases) {
            if (in_array($alias, $aliases)) {
                return $namespace;
            }
        }

        return $alias;
    }

    /**
     * Max files allowed for sitemap.
     *
     * @return int
     */
    public function itemsLimit(): int
    {
        return $this->config['sitemaps']['itemsLimit'];
    }

    /**
     * Max file size allowed for sitemap file.
     *
     * @return int
     */
    public function sizeLimit(): int
    {
        return $this->config['sitemaps']['sizeLimit'];
    }

    /**
     * Subdirectory for sitemap files.
     *
     * @return string
     */
    public function subDirectory(): string
    {
        return $this->config['sitemaps']['subDirectory'];
    }

    /**
     * Compression rate for sitemap file.
     *
     * @return mixed
     */
    public function compression()
    {
        return $this->config['sitemaps']['compression'];
    }
}