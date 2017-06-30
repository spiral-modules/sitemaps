<?php

namespace Spiral\Sitemaps;

use Spiral\Sitemaps\Interfaces\SitemapInterface;

class SitemapsState
{
    /** @var string */
    protected $filename;

    /** @var string */
    protected $directory;

    /**
     * Current sitemap.
     *
     * @var SitemapInterface
     */
    protected $current;

    /**
     * Array of all sitemaps to go into the index sitemap.
     *
     * @var SitemapInterface[]
     */
    protected $sitemaps = [];

    /**
     * Passed sitemap namespaces.
     *
     * @var array
     */
    protected $namespaces = [];

    public function __construct(string $filename, string $directory = null, array $namespaces = [])
    {
        $this->filename = $filename;
        $this->directory = $directory;
        $this->namespaces = $namespaces;
    }
}