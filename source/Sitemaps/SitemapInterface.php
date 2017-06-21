<?php

namespace Spiral\Sitemaps;

/**
 * @link https://www.sitemaps.org/ru/protocol.html
 */
interface SitemapInterface
{
    /**
     * @param int $filesCountLimit
     */
    public function setFilesCountLimit(int $filesCountLimit);

    /**
     * Set namespaces. File should not be opened by that time.
     *
     * @param array $namespaces
     * @throws \LogicException
     */
    public function setNamespaces(array $namespaces);

    /**
     * Open file.
     *
     * @param string $filename
     *
     * @throws \Exception
     */
    public function open(string $filename);

    /**
     * Close file.
     */
    public function close();
}