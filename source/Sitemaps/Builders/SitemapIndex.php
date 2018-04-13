<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Elements;

class SitemapIndex extends AbstractBuilder
{
    /**
     * @param Elements\Sitemap $sitemap
     *
     * @return bool
     */
    public function addSitemap(Elements\Sitemap $sitemap): bool
    {
        return $this->addElement($sitemap);
    }
}