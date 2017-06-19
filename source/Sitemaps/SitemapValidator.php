<?php

namespace Spiral\Sitemaps;

class SitemapValidator
{
    /** @var SitemapsConfig */
    protected $config;

    /**
     * SitemapValidator constructor.
     *
     * @param SitemapsConfig $config
     */
    public function __construct(SitemapsConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Is files count or file size limit reached for pages sitemap.
     *
     * @param SitemapInterface $sitemap
     *
     * @return bool
     */
    public function isPagesSitemapLimitReached(SitemapInterface $sitemap)
    {
        if ($sitemap->getItemsCount() >= $this->config->maxFiles('pages')) {
            //files count limit reached
            return true;
        }

        if ($sitemap->getFileSize() >= $this->config->maxFileSize('pages')) {
            //files count limit reached
            return true;
        }

        return false;
    }

    /**
     * Is files count limit reached for index sitemap.
     *
     * @param SitemapInterface $sitemap
     *
     * @return bool
     */
    public function isIndexSitemapLimitReached(SitemapInterface $sitemap)
    {
        return $sitemap->getItemsCount() >= $this->config->maxFiles('sitemaps');
    }
}