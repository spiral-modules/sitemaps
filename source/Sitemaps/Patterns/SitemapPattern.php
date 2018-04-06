<?php

namespace Spiral\Sitemaps\Patterns;

use Spiral\Sitemaps\Entities\Sitemap;
use Spiral\Sitemaps\EntityInterface;
use Spiral\Sitemaps\PatternInterface;

class SitemapPattern
{
    /**
     * @param \XMLWriter              $writer
     * @param EntityInterface|Sitemap $sitemap
     */
    public function write(\XMLWriter $writer, Sitemap $sitemap)
    {
        $writer->startElement('sitemap');

        $writer->writeElement('loc', $sitemap->getLocation());
        $writer->writeElement('lastmod', $sitemap->getLastModificationTime()->format('c'));

        $writer->endElement();
    }
}