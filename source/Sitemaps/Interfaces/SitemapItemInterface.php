<?php

namespace Spiral\Sitemaps\Interfaces;

/**
 * Interface SitemapItemInterface
 *
 * @package Spiral\Sitemaps\Interfaces
 */
interface SitemapItemInterface
{
    /**
     * Render sitemap item data into string.
     *
     * @return string
     */
    public function render(): string;
}