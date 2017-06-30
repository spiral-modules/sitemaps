<?php

namespace Spiral\Sitemaps\Sitemaps;

use Spiral\Sitemaps\Interfaces\SitemapInterface;

class IndexSitemap extends AbstractSitemap
{
    const ROOT_NODE_TAG = 'sitemapindex';

    /**
     * @param null|int $itemsLimit
     */
    public function __construct(int $itemsLimit = null)
    {
        $this->itemsLimit = $itemsLimit;
    }

    /**
     * Add sitemap file.
     *
     * @param SitemapInterface $sitemap
     *
     * @return bool
     */
    public function addSitemap(SitemapInterface $sitemap)
    {
        return $this->add($sitemap);
    }
}