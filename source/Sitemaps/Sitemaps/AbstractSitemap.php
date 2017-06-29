<?php

namespace Spiral\Sitemaps\Sitemaps;

use Spiral\Sitemaps\Exceptions\SitemapLogicException;
use Spiral\Sitemaps\Exceptions\HandlerRuntimeException;
use Spiral\Sitemaps\ItemInterface;
use Spiral\Sitemaps\SitemapInterface;

abstract class AbstractSitemap implements SitemapInterface
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
    protected $filesCountLimit = null;

    /**
     * Files amount counter.
     *
     * @var int
     */
    protected $countItems = 0;

    /**
     * @param array    $namespaces
     * @param null|int $filesCountLimit
     */
    public function __construct(array $namespaces = [], int $filesCountLimit = null)
    {
        $this->namespaces = $namespaces;
        $this->filesCountLimit = $filesCountLimit;
    }

    /**
     * {@inheritdoc}
     *
     * @throws HandlerRuntimeException
     */
    public function open(string $filename)
    {
        if (!$this->isOpened()) {
            $this->openHandler($filename);

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
     * @param ItemInterface $item
     *
     * @return bool
     */
    protected function add(ItemInterface $item): bool
    {
        if ($this->isFilesCountLimitReached()) {
            return false;
        }

        if (!$this->isOpened()) {
            throw new SitemapLogicException('Unable to add data to file. File should be opened first.');
        }

        $this->incrementFilesCounter();
        $this->writeData($item->render());

        return true;
    }

    /**
     * Files count limit is set and current counter has reached it.
     *
     * @return bool
     */
    protected function isFilesCountLimitReached(): bool
    {
        return !empty($this->filesCountLimit) && $this->countItems >= $this->filesCountLimit;
    }

    /**
     * Incrementing files count.
     */
    protected function incrementFilesCounter()
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
     *
     * @param string $filename
     */
    protected function openHandler(string $filename)
    {
        $this->handler = fopen($filename, 'wb');
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
        $this->writeData(sprintf('<%s %s>', static::ROOT_NODE_TAG, $this->namespaces()));
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