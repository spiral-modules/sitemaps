<?php

namespace Spiral\Sitemaps\Interfaces;

/**
 * Sitemap file writer, can open and close file.
 * Interface SitemapWriterInterface
 *
 * @package Spiral\Sitemaps\Interfaces
 */
interface SitemapWriterInterface
{
    /**
     * Open file.
     *
     * @param string $filename
     */
    public function open(string $filename);

    /**
     * Close file.
     */
    public function close();
}