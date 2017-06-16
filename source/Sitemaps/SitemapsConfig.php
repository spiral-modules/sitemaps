<?php

namespace Spiral\Sitemaps;

use InvalidArgumentException;
use Spiral\Core\InjectableConfig;

class SitemapsConfig extends InjectableConfig
{
    const CONFIG = 'sitemaps';

    protected $config = [
        'xmlHeader'       => '<?xml version="1.0" encoding="UTF-8"?>',
        'sitemaps'        => [
            'pages'    => [
                'wrapTag'     => 'urlset',
                'maxFiles'    => 50000,
                //49.59mb actually - a little bit smaller than 50mb, enough to write closing tag
                'maxFileSize' => 52000000,
            ],
            'sitemaps' => [
                'wrapTag'  => 'sitemapindex',
                'maxFiles' => 500
            ],
        ],
        'directory'       => 'sitemaps/',
        'knownNamespaces' => [
            'default'   => 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"',
            'image'     => 'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"',
            'video'     => 'xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"',
            'alterLang' => 'xmlns:xhtml="http://www.w3.org/1999/xhtml"',
        ]
    ];

    /**
     * List of know namespaces to allow using aliases.
     *
     * @return array
     */
    public function knownNamespaces(): array
    {
        return $this->config['knownNamespaces'];
    }

    /**
     * Get namespace by short alias provided.
     *
     * @param string $namespace
     * @return string
     */
    public function getNamespace(string $namespace): string
    {
        return $this->config['knownNamespaces'][$namespace] ?? $namespace;
    }

    /**
     * Wrap tag for wrapper.
     *
     * @param string $wrapper
     * @return string
     */
    public function wrapTag(string $wrapper): string
    {
        if (isset($this->config['sitemaps'][$wrapper]['wrapTag'])) {
            return $this->config['sitemaps'][$wrapper]['wrapTag'];
        }

        throw new \InvalidArgumentException("Unsupported wrapper \"$wrapper\".");
    }

    /**
     * Max files allowed for wrapper.
     *
     * @param string $wrapper
     * @return int
     */
    public function maxFiles(string $wrapper): int
    {
        if (isset($this->config['sitemaps'][$wrapper]['maxFiles'])) {
            return $this->config['sitemaps'][$wrapper]['maxFiles'];
        }

        throw new \InvalidArgumentException("Unsupported wrapper \"$wrapper\".");
    }

    /**
     * Basic XML file header.
     *
     * @return string
     */
    public function xmlHeader(): string
    {
        return $this->config['xmlHeader'];
    }

    /**
     * Max file size allowed for sitemap file.
     *
     * @param string $wrapper
     * @return int
     */
    public function maxFileSize(string $wrapper): int
    {
        if (isset($this->config['sitemaps'][$wrapper]['maxFileSize'])) {
            return $this->config['sitemaps'][$wrapper]['maxFileSize'];
        }

        throw new \InvalidArgumentException("Unsupported wrapper \"$wrapper\".");
    }

    /**
     * Subdirectory for sitemap files.
     *
     * @return string
     */
    public function sitemapsDirectory(): string
    {
        return $this->config['directory'];
    }
}