<?php

namespace Spiral\Sitemaps;

use \Spiral\Sitemaps\Exceptions\AlreadyOpenedSitemapException;

/**
 * @link https://www.sitemaps.org/ru/protocol.html
 */
interface SitemapInterface
{
    /**
     * @param int $filesCountLimit
     *
     * @throws AlreadyOpenedSitemapException
     */
    public function setFilesCountLimit(int $filesCountLimit);

    /**
     * Set namespaces. File should not be opened by that time.
     *
     * @param array $namespaces
     *
     * @throws AlreadyOpenedSitemapException
     */
    public function setNamespaces(array $namespaces);

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