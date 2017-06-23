<?php

namespace Spiral\Sitemaps;

use Spiral\Files\FileManager;
use Spiral\Sitemaps\Sitemaps\IndexSitemap;
use Spiral\Sitemaps\Sitemaps\Sitemap;

class Sitemaps
{
    /** @var SitemapsConfig */
    protected $config;

    /** @var string */
    protected $filename;

    /** @var string */
    protected $directory;

    /** @var FileManager */
    protected $files;

    /**
     * Current pages sitemap.
     *
     * @var Sitemap
     */
    protected $currentSitemap;

    /**
     * Array of all pages sitemaps in the index sitemap.
     *
     * @var Sitemap[]
     */
    protected $sitemaps = [];

    /**
     * Passed sitemap namespaces.
     *
     * @var array
     */
    protected $namespaces = [
        'sitemap' => [],
        'index'   => []
    ];

    /**
     * @param SitemapsConfig $config
     * @param FileManager    $files
     */
    public function __construct(SitemapsConfig $config, FileManager $files)
    {
        $this->config = $config;
        $this->files = $files;
    }

    /**
     * Set sitemap namespaces.
     *
     * @param array $namespaces
     */
    public function setSitemapNamespaces(array $namespaces)
    {
        $this->namespaces['sitemap'] = $namespaces;
    }

    /**
     * Set index sitemap namespaces.
     *
     * @param array $namespaces
     */
    public function setIndexSitemapNamespaces(array $namespaces)
    {
        $this->namespaces['index'] = $namespaces;
    }

    /**
     * Open sitemap.
     *
     * @param string      $filename
     * @param string|null $directory
     */
    public function open(string $filename, string $directory = null)
    {
        if (!empty($this->currentSitemap)) {
            //already opened.
            return;
        }

        $this->filename = $filename;
        $this->directory = $directory;

        $this->newSitemap();
    }

    /**
     * Add item to sitemap.
     *
     * @param ItemInterface $item
     *
     * @return bool
     */
    public function addItem(ItemInterface $item)
    {
        if (empty($this->currentSitemap)) {
            throw new \LogicException('Call "open" method first.');
        }

        if (!$this->currentSitemap->addItem($item)) {
            //Previous sitemap is full, create new.
            $this->newSitemap();
        }

        return $this->currentSitemap->addItem($item);
    }

    /**
     * Close sitemap.
     *
     * @return null|SitemapInterface
     * @throws \Exception
     */
    public function close()
    {
        //Close current sitemap, if opened
        if (!empty($this->currentSitemap)) {
            $this->currentSitemap->close();
        }

        //only one sitemap
        if (!count($this->sitemaps)) {
            return $this->currentSitemap;
        }

        $this->storeCurrentSitemap();

        return $this->packIndexSitemap();
    }

    /**
     * Pack all sitemap files int index sitemap.
     *
     * @return IndexSitemap
     */
    protected function packIndexSitemap(): IndexSitemap
    {
        $index = $this->makeIndexSitemap();
        $index->open($this->directory . $this->filename);

        foreach ($this->sitemaps as $filename => $sitemap) {
            if (!$index->addSitemap($sitemap)) {
                throw new \OverflowException(sprintf(
                    'Sitemap Index is full, "%s" limit is reached (%s actual sitemaps)',
                    $this->config->maxFiles('index'),
                    count($this->sitemaps)
                ));
            }
        }

        $index->close();

        return $index;
    }

    /**
     * Create and open new sitemap.
     */
    public function newSitemap()
    {
        if (!empty($this->currentSitemap)) {
            $this->currentSitemap->close();
            $this->storeCurrentSitemap();
        }

        $this->currentSitemap = $this->makeSitemap();
        $this->currentSitemap->open($this->directory . $this->filename, $this->config->compression());
    }

    /**
     * Create sitemap.
     *
     * @return SitemapInterface
     */
    protected function makeSitemap(): SitemapInterface
    {
        return new Sitemap(
            $this->namespaces['sitemap'],
            $this->config->maxFiles('sitemap'),
            $this->config->maxFileSize('sitemap')
        );
    }

    /**
     * Create index sitemap.
     *
     * @return IndexSitemap
     */
    protected function makeIndexSitemap(): IndexSitemap
    {
        return new IndexSitemap(
            $this->namespaces['index'],
            $this->config->maxFiles('index')
        );
    }

    /**
     * Destructing.
     */
    public function __destruct()
    {
        if (!empty($this->currentSitemap)) {
            $this->close();
        }
    }

    /**
     * Rename and move current sitemap. Store it in sitemaps array.
     */
    protected function storeCurrentSitemap()
    {
        if (!empty($this->currentSitemap)) {
            $subDirectory = $this->config->subDirectory();
            if (!empty($subDirectory)) {
                $this->files->ensureDirectory($this->directory . $subDirectory);
            }

            $destinationFilename = $this->destinationFilename($this->filename);
            $this->files->move($this->filename, $this->directory . $destinationFilename);

            $this->sitemaps[$destinationFilename] = $this->currentSitemap;
        }
    }

    /**
     * Make filename for a moved pages sitemap.
     *
     * @param string $filename
     *
     * @return string
     */
    protected function destinationFilename(string $filename): string
    {
        return $this->config->subDirectory() . (count($this->sitemaps) + 1) . '-' . basename($filename);
    }
}