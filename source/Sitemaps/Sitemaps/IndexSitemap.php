<?php

namespace Spiral\Sitemaps\Sitemaps;

class IndexSitemap extends AbstractSitemap
{
    const ROOT_NODE_TAG = 'sitemapindex';

    /**
     * Add sitemap file.
     *
     * @param Sitemap $sitemap
     *
     * @return bool
     */
    public function addSitemap(Sitemap $sitemap)
    {
        return $this->add($sitemap);
    }
}