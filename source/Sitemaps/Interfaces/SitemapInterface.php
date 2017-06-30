<?php

namespace Spiral\Sitemaps\Interfaces;

/**
 * Is a sitemap file. Can render itself for sitemap index file.
 * Interface SitemapInterface
 *
 * @package Spiral\Sitemaps\Interfaces
 */
interface SitemapInterface extends SitemapItemInterface, SitemapWriterInterface
{
    /**
     * Add sitemap item.
     *
     * @param SitemapItemInterface $item
     *
     * @return bool
     */
    public function addItem(SitemapItemInterface $item): bool;
}