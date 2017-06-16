<?php

namespace Spiral\Sitemaps;

use Spiral\Files\FileManager;
use Spiral\Sitemaps\Sitemaps\IndexSitemap;
use Spiral\Sitemaps\Sitemaps\PagesSitemap;
use Spiral\Sitemaps\Wrappers\PagesWrapper;
use Spiral\Sitemaps\Wrappers\SitemapsWrapper;

class Sitemaps
{
    /** @var PagesWrapper */
    protected $pagesWrapper;

    /** @var SitemapsWrapper */
    protected $sitemapsWrapper;

    /** @var SitemapsConfig */
    protected $config;

    /** @var string */
    protected $filename;

    /** @var bool */
    protected $compress;

    /** @var string */
    protected $directory;

    /** @var FileManager */
    protected $files;

    /**
     * Current pages sitemap.
     *
     * @var PagesSitemap
     */
    protected $currentSitemap;

    /**
     * Array of all pages sitemaps in the index sitemap.
     *
     * @var PagesSitemap[]
     */
    protected $sitemaps = [];

    /**
     * Validate sitemap's size and items count.
     *
     * @var SitemapValidator
     */
    protected $validator;

    /**
     * @param SitemapsConfig   $config
     * @param FileManager      $files
     * @param SitemapValidator $validator
     * @param SitemapsWrapper  $sitemapsWrapper
     */
    public function __construct(
        SitemapsConfig $config,
        FileManager $files,
        SitemapValidator $validator,
        SitemapsWrapper $sitemapsWrapper
    ) {
        $this->config = $config;
        $this->files = $files;
        $this->validator = $validator;
        $this->sitemapsWrapper = $sitemapsWrapper;
    }

    /**
     * @param WrapperInterface $wrapper
     */
    public function setWrapper(WrapperInterface $wrapper)
    {
        if (!empty($this->currentSitemap)) {
            throw new \LogicException("Wrapper can't be set after process is opened.");
        }

        $this->pagesWrapper = $wrapper;
    }

    /**
     * Open sitemap.
     *
     * @param string $filename
     * @param string $directory
     * @param bool   $compress
     */
    public function open(string $filename, string $directory, bool $compress = false)
    {
        if (empty($this->pagesWrapper) || empty($this->sitemapsWrapper)) {
            throw new \LogicException("Wrappers should be set first.");
        }

        $this->filename = $filename;
        $this->compress = $compress;
        $this->directory = $directory;

        $this->createAndOpenSitemap();
    }

    /**
     * Add item to sitemap.
     *
     * @param ItemInterface $item
     * @return int
     */
    public function addItem(ItemInterface $item): int
    {
        if (empty($this->currentSitemap)) {
            throw new \LogicException("Sitemap process should be opened first.");
        }

        if ($this->shouldCreateNewFile()) {
            $this->createAndOpenSitemap();
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
            if ($this->validator->isIndexSitemapLimitReached($index)) {
                throw new \OverflowException(sprintf(
                    'Sitemap Index is full, "%s" limit is reached (%s actual sitemaps)',
                    $index->getItemsCount(),
                    count($this->sitemaps)
                ));
            }

            $index->addSitemap($sitemap);
        }

        $index->close();

        return $index;
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
     * Should new pages sitemap be created?
     *
     * @return bool
     */
    protected function shouldCreateNewFile(): bool
    {
        return $this->validator->isPagesSitemapLimitReached($this->currentSitemap);
    }

    /**
     * Create and open new pages sitemap.
     */
    protected function createAndOpenSitemap()
    {
        if (!empty($this->currentSitemap)) {
            $this->currentSitemap->close();
            $this->storeCurrentSitemap();
            $this->currentSitemap = null;
        }

        $this->currentSitemap = new PagesSitemap($this->pagesWrapper);
        $this->currentSitemap->open($this->directory . $this->filename, $this->compress);
    }

    /**
     * Rename and move current sitemap. Store it in sitemaps array.
     */
    protected function storeCurrentSitemap()
    {
        $filename = $this->currentSitemap->getFilename();
        $movedFilename = $this->makeMovedFilename($filename);

        $this->files->ensureDirectory($this->directory . $this->config->sitemapsDirectory());
        $this->files->move($filename, $this->directory . $movedFilename);
        $this->sitemaps[$movedFilename] = $this->currentSitemap->withFilename($this->directory . $movedFilename);
    }

    /**
     * Make filename for a moved pages sitemap.
     *
     * @param string $filename
     * @return string
     */
    protected function makeMovedFilename(string $filename): string
    {
        return $this->config->sitemapsDirectory() . (count($this->sitemaps) + 1) . '-' . basename($filename);
    }
}