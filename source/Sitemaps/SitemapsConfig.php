<?php

namespace Spiral\Sitemaps;

use Spiral\Core\InjectableConfig;
use Spiral\Sitemaps\Exceptions\UnknownSitemapException;

class SitemapsConfig extends InjectableConfig
{
    const CONFIG = 'modules/sitemaps';

    protected $config = [
        'sitemaps' => [
            'sitemap' => [
                'maxFiles'     => 50000,
                //49.59mb actually - a little bit smaller than 50mb, enough to write closing tag
                'maxFileSize'  => 52000000,
                'subDirectory' => 'sitemaps/',
                'compression'  => true,
            ],
            'index'   => [
                'maxFiles' => 500
            ],
        ],
        'aliases'  => [
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
        foreach ($this->config['aliases'] as $namespace => $aliases) {
            if (in_array($alias, $aliases)) {
                return $namespace;
            }
        }

        return $alias;
    }

    /**
     * Max files allowed for sitemap.
     *
     * @param string $sitemap
     *
     * @return int
     */
    public function maxFiles(string $sitemap): int
    {
        if (isset($this->config['sitemaps'][$sitemap]['maxFiles'])) {
            return $this->config['sitemaps'][$sitemap]['maxFiles'];
        }

        throw new UnknownSitemapException($sitemap);
    }

    /**
     * Max file size allowed for sitemap file.
     *
     * @param string $sitemap
     *
     * @return int
     */
    public function maxFileSize(string $sitemap): int
    {
        if (isset($this->config['sitemaps'][$sitemap]['maxFileSize'])) {
            return $this->config['sitemaps'][$sitemap]['maxFileSize'];
        }

        throw new UnknownSitemapException($sitemap);
    }

    /**
     * Subdirectory for sitemap files.
     *
     * @return string
     */
    public function subDirectory(): string
    {
        return $this->config['sitemaps']['sitemap']['subDirectory'];
    }

    /**
     * Compression rate for sitemap file.
     *
     * @return mixed
     */
    public function compression()
    {
        return $this->config['sitemaps']['sitemap']['compression'];
    }
}