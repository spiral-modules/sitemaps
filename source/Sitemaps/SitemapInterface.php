<?php

namespace Spiral\Sitemaps;

/**
 * @link https://www.sitemaps.org/ru/protocol.html
 */
interface SitemapInterface
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