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
    protected $current = null;

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

    protected $options = [];

    protected $compression = null;

    public function __construct(string $filename, string $directory = null, array $options = [])
    {
        $this->filename = $filename;
        $this->directory = $directory;
        $this->options = $options;
    }

    public function sequencedFilename()
    {
        return (count($this->sitemaps) + 1) . '-' . basename($this->filename);
    }

    public function getDestination(string $subDirectory = null)
    {
        return $this->directory . $subDirectory . $this->sequencedFilename();
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function getFilename(): string
    {
        return $this->directory . $this->filename;
    }

    /**
     * @return SitemapInterface|null
     */
    public function getSitemap()
    {
        return $this->current;
    }

    public function setSitemap(SitemapInterface $sitemap = null)
    {
        $this->current = $sitemap;
    }

    public function getNamespaces(): array
    {
        return !empty($this->options['namespaces']) && is_array($this->options['namespaces']) ? $this->options['namespaces'] : [];
    }

    /**
     * @return int|null
     */
    public function getItemsLimit()
    {
        return array_key_exists('itemsLimit', $this->options) ? $this->options['itemsLimit'] : null;
    }

    /**
     * @return int|null
     */
    public function getSizeLimit()
    {
        return array_key_exists('sizeLimit', $this->options) ? $this->options['sizeLimit'] : null;
    }

    /**
     * @return int|bool|null
     */
    public function getCompression()
    {
        return array_key_exists('compression', $this->options) ? $this->options['compression'] : null;
    }

    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }
}