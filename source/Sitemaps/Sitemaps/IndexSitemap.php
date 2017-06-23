<?php

namespace Spiral\Sitemaps\Sitemaps;

class IndexSitemap extends AbstractSitemap
{
    const ROOT_NODE_TAG = 'sitemapindex';

    /**
     * Add sitemap file.
     *
     * @param Sitemap $item
     *
     * @return bool
     */
    public function addSitemap(Sitemap $item)
    {
        return $this->add($item);
    }
}