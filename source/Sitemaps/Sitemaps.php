<?php

namespace Spiral\Sitemaps;

use Spiral\Files\FileManager;
use Spiral\Sitemaps\Sitemaps\IndexSitemap;
use Spiral\Sitemaps\Sitemaps\Sitemap;
use Spiral\Sitemaps\Wrappers\SitemapWrapper;
use Spiral\Sitemaps\Wrappers\IndexSitemapWrapper;

class Sitemaps
{
    /** @var SitemapWrapper */
    protected $pagesWrapper;

    /** @var IndexSitemapWrapper */
    protected $sitemapsWrapper;

    /** @var SitemapsConfig */
    protected $config;

    /** @var string */
    protected $filename;

    /** @var int|null */
    protected $compress;

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
     * @param SitemapsConfig $config
     * @param FileManager    $files
     */
    public function __construct(SitemapsConfig $config, FileManager $files)
    {
        $this->config = $config;
        $this->files = $files;
    }

    /**
     * @param WrapperInterface $wrapper
     */
    public function setSitemapWrapper(WrapperInterface $wrapper)
    {
        if (!empty($this->currentSitemap)) {
            throw new \LogicException("Wrapper can't be set after process is opened.");
        }

        $this->pagesWrapper = $wrapper;
    }

    /**
     * @param WrapperInterface $wrapper
     */
    public function setIndexSitemapWrapper(WrapperInterface $wrapper)
    {
        if (!empty($this->currentSitemap)) {
            throw new \LogicException("Wrapper can't be set after process is opened.");
        }

        $this->sitemapsWrapper = $wrapper;
    }

    /**
     * Open sitemap.
     *
     * @param string   $filename
     * @param string   $directory
     * @param int|null $compress
     */
    public function open(string $filename, string $directory, int $compress = null)
    {
        if (!empty($this->currentSitemap)) {
            //already opened.
            return;
        }

        if (empty($this->pagesWrapper) || empty($this->sitemapsWrapper)) {
            throw new \LogicException("Wrappers should be set first.");
        }

        $this->filename = $filename;
        $this->compress = $compress;
        $this->directory = $directory;

        $this->createNewSitemap();
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
            throw new \LogicException("Sitemap process should be opened first.");
        }

        if (!$this->currentSitemap->addItem($item)) {
            //Previous sitemap is full, create new.
            $this->createNewSitemap();
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
        if (empty($this->currentSitemap)) {
            return null;
        }

        $this->currentSitemap->close();

        if (!count($this->sitemaps)) {
            $sitemap = $this->currentSitemap;
            $this->currentSitemap = null;

            return $sitemap;
        }

        $this->storeCurrentSitemap();
        $this->currentSitemap = null;

        $index = $this->makeIndexSitemap();
        $index->open($this->directory . $this->filename);

        foreach ($this->sitemaps as $filename => $sitemap) {
            if (!$index->addSitemap($sitemap)) {
                throw new \OverflowException(sprintf(
                    'Sitemap Index is full, "%s" limit is reached (%s actual sitemaps)',
                    $index->getItemsCount(),
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
    public function createNewSitemap()
    {
        if (!empty($this->currentSitemap)) {
            $this->currentSitemap->close();
            $this->storeCurrentSitemap();
            $this->currentSitemap = null;
        }

        $this->openSitemap();
    }

    /**
     * Create index sitemap.
     *
     * @return IndexSitemap
     */
    protected function makeIndexSitemap(): IndexSitemap
    {
        return new IndexSitemap($this->sitemapsWrapper);
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
     * Open new sitemap.
     */
    protected function openSitemap()
    {
        $this->currentSitemap = new Sitemap($this->pagesWrapper);
        $this->currentSitemap->open($this->directory . $this->filename, $this->compress);
    }

    /**
     * Rename and move current sitemap. Store it in sitemaps array.
     */
    protected function storeCurrentSitemap()
    {
        $movedFilename = $this->makeMovedFilename($this->filename);

        $this->files->ensureDirectory($this->directory . $this->config->sitemapsDirectory());
        $this->files->move($this->filename, $this->directory . $movedFilename);

        $this->sitemaps[$movedFilename] = $this->currentSitemap;
    }

    /**
     * Make filename for a moved pages sitemap.
     *
     * @param string $filename
     *
     * @return string
     */
    protected function makeMovedFilename(string $filename): string
    {
        return $this->config->sitemapsDirectory() . (count($this->sitemaps) + 1) . '-' . basename($filename);
    }
}