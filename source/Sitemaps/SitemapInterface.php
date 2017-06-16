<?php

namespace Spiral\Sitemaps;

interface SitemapInterface
{
    /**
     * Sitemap items count.
     *
     * @return int
     */
    public function getItemsCount(): int;

    /**
     * Sitemap file size.
     *
     * @return int
     */
    public function getFileSize(): int;

    /**
     * Sitemap filename.
     *
     * @return string
     */
    public function getFilename(): string;
}