<?php

namespace Spiral\Sitemaps\Sitemaps;

class IndexSitemap extends AbstractSitemap
{
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