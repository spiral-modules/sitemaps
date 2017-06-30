<?php

namespace Spiral\Sitemaps\Sitemaps;

use Spiral\Sitemaps\Exceptions\SitemapLogicException;
use Spiral\Sitemaps\Exceptions\HandlerRuntimeException;
use Spiral\Sitemaps\Interfaces\SitemapItemInterface;
use Spiral\Sitemaps\Interfaces\SitemapWriterInterface;

abstract class AbstractSitemap implements SitemapWriterInterface
{
    /**
     * Basic XML header tag.
     */
    const DECLARATION = '<?xml version="1.0" encoding="UTF-8"?>';

    /**
     * Default sitemap root node namespace.
     */
    const DEFAULT_NS = 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';

    /**
     * Root node tag, urlset or sitemapindex.
     */
    const ROOT_NODE_TAG = null;

    /**
     * File resource handler.
     *
     * @var null|resource
     */
    protected $handler = null;

    /**
     * Sitemap namespaces.
     *
     * @var array
     */
    protected $namespaces = [];

    /**
     * Allowed sitemap files count, if set.
     *
     * @var null|int
     */
    protected $itemsLimit = null;

    /**
     * Files amount counter.
     *
     * @var int
     */
    protected $countItems = 0;

    /**
     * Filename of current sitemap.
     *
     * @var null|string
     */
    protected $filename = null;

    /**
     * {@inheritdoc}
     *
     * @throws HandlerRuntimeException
     */
    public function open(string $filename)
    {
        if (!$this->isOpened()) {
            $this->filename = $filename;
            $this->openHandler();

            if (!$this->isOpened()) {
                throw new HandlerRuntimeException('File handler opening operation failed.');
            }

            $this->writeDeclaration();
            $this->writeOpenRootNodeTag();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if ($this->isOpened()) {
            $this->writeCloseRootNodeTag();
            $this->closeHandler();
        }

        $this->handler = null;
    }

    /**
     * Destructing.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Add sitemap item.
     *
     * @param SitemapItemInterface $item
     *
     * @return bool
     */
    protected function add(SitemapItemInterface $item): bool
    {
        if ($this->isItemsLimitReached()) {
            return false;
        }

        if (!$this->isOpened()) {
            throw new SitemapLogicException('Unable to add data to file. File should be opened first.');
        }

        $this->incrementItemsCounter();
        $this->writeData($item->render());

        return true;
    }

    /**
     * Files count limit is set and current counter has reached it.
     *
     * @return bool
     */
    protected function isItemsLimitReached(): bool
    {
        return !empty($this->itemsLimit) && $this->countItems >= $this->itemsLimit;
    }

    /**
     * Incrementing files count.
     */
    protected function incrementItemsCounter()
    {
        $this->countItems++;
    }

    /**
     * Is file handler opened.
     *
     * @return bool
     */
    protected function isOpened(): bool
    {
        return !empty($this->handler);
    }

    /**
     * Close file handler.
     */
    protected function openHandler()
    {
        $this->handler = fopen($this->filename, 'wb');
    }

    /**
     * Write data portion.
     *
     * @param $data
     */
    protected function writeData($data)
    {
        $this->writeIntoHandler($data);
    }

    /**
     * Write sitemap XML declaration.
     */
    protected function writeDeclaration()
    {
        $this->writeData(static::DECLARATION);
    }

    /**
     * Write open root node tag.
     */
    protected function writeOpenRootNodeTag()
    {
        $namespaces = $this->namespaces();
        if (empty($namespaces)) {
            $this->writeData(sprintf('<%s>', static::ROOT_NODE_TAG));
        } else {
            $this->writeData(sprintf('<%s %s>', static::ROOT_NODE_TAG, $this->namespaces()));
        }
    }

    /**
     * Write close root node tag.
     */
    protected function writeCloseRootNodeTag()
    {
        $this->writeData(sprintf('</%s>', static::ROOT_NODE_TAG));
    }

    /**
     * Write data into file handler.
     *
     * @param $data
     *
     * @return int
     */
    protected function writeIntoHandler($data): int
    {
        return fwrite($this->handler, $data);
    }

    /**
     * Close file handler.
     */
    protected function closeHandler()
    {
        fclose($this->handler);
    }

    /**
     * List of namespaces, string formatted.
     *
     * @return string
     */
    protected function namespaces(): string
    {
        $namespaces = $this->namespaces;
        $namespaces[] = static::DEFAULT_NS;

        return join(' ', array_unique($namespaces));
    }
}