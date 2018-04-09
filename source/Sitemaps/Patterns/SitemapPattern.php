<?php

namespace Spiral\Sitemaps\Patterns;

use Spiral\Sitemaps\Elements\Sitemap;
use Spiral\Sitemaps\ElementInterface;
use Spiral\Sitemaps\PatternInterface;

class SitemapPattern implements PatternInterface
{
    /**
     * @param \XMLWriter               $writer
     * @param ElementInterface|Sitemap $sitemap
     */
    public function write(\XMLWriter $writer, Sitemap $sitemap)
    {
        $writer->startElement('sitemap');

        $writer->writeElement('loc', $sitemap->getLocation());
        $writer->writeElement('lastmod', $sitemap->getLastModificationTime()->format('c'));

        $writer->endElement();
    }
}