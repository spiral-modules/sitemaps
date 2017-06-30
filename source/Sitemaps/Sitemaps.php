<?php

namespace Spiral\Sitemaps;

use Spiral\Files\FileManager;
use Spiral\Sitemaps\Exceptions\SitemapLogicException;
use Spiral\Sitemaps\Interfaces\SitemapInterface;
use Spiral\Sitemaps\Interfaces\SitemapItemInterface;
use Spiral\Sitemaps\Interfaces\SitemapWriterInterface;
use Spiral\Sitemaps\Sitemaps\IndexSitemap;
use Spiral\Sitemaps\Sitemaps\Sitemap;

class Sitemaps implements SitemapWriterInterface
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
    protected $namespaces = [];

    protected $state = null;

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
        $this->namespaces = $namespaces;
    }

    /**
     * Open sitemap.
     *
     * @param string      $filename
     * @param string|null $directory
     * @param array       $namespaces
     */
    public function open(string $filename, string $directory = null, array $namespaces = [])
    {
        if (empty($this->state)) {
            $this->state = new SitemapsState($filename, $directory, $namespaces);

            $this->filename = $filename;
            $this->directory = $directory;

            $this->openSitemap($this->namespaces);
        }
    }

    /**
     * Add item to sitemap.
     *
     * @param SitemapItemInterface $item
     *
     * @return bool
     */
    public function addItem(SitemapItemInterface $item)
    {
        if (empty($this->currentSitemap)) {
            throw new SitemapLogicException('Unable to add item. File should be opened first.');
        }

        //Previous sitemap is full, create new.
        if (!$this->currentSitemap->addItem($item)) {
            $this->restartSitemap();
        }

        return $this->currentSitemap->addItem($item);
    }

    /**
     * Close sitemap.
     *
     * @return IndexSitemap|Sitemap
     */
    public function close()
    {
        //Close current sitemap, if opened
        $this->closeSitemap();

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
    private function packIndexSitemap(): IndexSitemap
    {
        $index = $this->makeIndexSitemap();
        $index->open($this->directory . $this->filename);

        foreach ($this->sitemaps as $filename => $sitemap) {
            if (!$index->addSitemap($sitemap)) {
                throw new \OverflowException(sprintf(
                    'Sitemap Index is full, "%s" limit is reached (%s actual sitemaps)',
                    $this->config->maxFiles(),
                    count($this->sitemaps)
                ));
            }
        }

        $index->close();

        return $index;
    }

    /**
     * Create and open new sitemap.
     *
     * @param array $namespaces
     */
    private function openSitemap(array $namespaces)
    {
        $this->currentSitemap = $this->makeSitemap(
            $namespaces,
            $this->config->maxFiles(),
            $this->config->maxFileSize()
        );

        $this->currentSitemap->open($this->directory . $this->filename, $this->config->compression());
    }

    /**
     * Close current sitemap if not empty.
     */
    protected function closeSitemap()
    {
        if (!empty($this->currentSitemap)) {
            $this->currentSitemap->close();
        }
    }

    /**
     *
     */
    private function restartSitemap()
    {
        $this->storeSitemap();
        $this->openSitemap($this->namespaces);
    }

    /**
     *
     */
    private function storeSitemap()
    {
        $this->closeSitemap();
        $this->storeCurrentSitemap();
    }

    /**
     * @param array|null $namespaces
     */
    public function startSitemap(array $namespaces = null)
    {
        $this->storeSitemap();
        $this->openSitemap(is_array($namespaces) ? $namespaces : $this->namespaces);
    }

    /**
     * Create sitemap.
     *
     * @param array $namespaces
     * @param int   $filesCountLimit
     * @param int   $fileSizeLimit
     *
     * @return SitemapInterface
     */
    private function makeSitemap(array $namespaces, int $filesCountLimit, int $fileSizeLimit): SitemapInterface
    {
        return new Sitemap($namespaces, $filesCountLimit, $fileSizeLimit);
    }

    /**
     * Create index sitemap.
     *
     * @return IndexSitemap
     */
    private function makeIndexSitemap(): IndexSitemap
    {
        return new IndexSitemap($this->config->maxFiles());
    }

    /**
     * Destructing.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Rename and move current sitemap. Store it in sitemaps array.
     */
    private function storeCurrentSitemap()
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
    private function destinationFilename(string $filename): string
    {
        return $this->config->subDirectory() . (count($this->sitemaps) + 1) . '-' . basename($filename);
    }
}