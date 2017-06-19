<?php

namespace Spiral\Sitemaps\Sitemaps;

class IndexSitemap extends AbstractSitemap
{
    /**
     * Add sitemap file.
     *
     * @param PagesSitemap $item
     *
     * @return int
     */
    public function addSitemap(PagesSitemap $item): int
    {
        return $this->add($item);
    }
}