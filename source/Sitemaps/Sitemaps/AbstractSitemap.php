<?php

namespace Spiral\Sitemaps\Sitemaps;

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
     * Filename of current sitemap.
     *
     * @var null|string
     */
    protected $filename = null;

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
     * Allowed sitemap file limit in bytes, if set.
     *
     * @var null|int
     */
    protected $fileSizeLimit = null;

    /**
     * File size counter.
     *
     * @var int
     */
    protected $fileSize = 0;

    /**
     * Files amount counter.
     *
     * @var int
     */
    protected $countItems = 0;

    /**
     * @param array    $namespaces
     * @param null|int $filesCountLimit
     * @param null|int $fileSizeLimit
     */
    public function __construct(
        array $namespaces = [],
        int $filesCountLimit = null,
        int $fileSizeLimit = null
    ) {
        $this->namespaces = $namespaces;
        $this->filesCountLimit = $filesCountLimit;
        $this->fileSizeLimit = $fileSizeLimit;
    }

    /**
     * {@inheritdoc}
     */
    public function setNamespaces(array $namespaces)
    {
        if ($this->isOpened()) {
            throw new \LogicException('Unable to set namespaces - sitemap is already opened.');
        }

        $this->namespaces = $namespaces;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilesCountLimit(int $filesCountLimit)
    {
        if ($this->isOpened()) {
            throw new \LogicException(sprintf(
                'Unable to set files count limit "%s" - sitemap is already opened.',
                $filesCountLimit
            ));
        }

        $this->filesCountLimit = $filesCountLimit;
    }

    /**
     * {@inheritdoc}
     */
    public function open(string $filename)
    {
        if ($this->isOpened()) {
            //already opened.
            return;
        }

        $this->filename = $filename;

        $this->openHandler();
        $this->writeDeclaration();
        $this->openRootNode();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if ($this->isOpened()) {
            $this->closeRootNode();
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
     * @throws \Exception
     */
    protected function add(ItemInterface $item): bool
    {
        if (!empty($this->filesCountLimit) && $this->countItems >= $this->filesCountLimit) {
            //files count limit is set and current counter has reached it.
            return false;
        }

        if (!empty($this->fileSizeLimit) && $this->fileSize >= $this->fileSizeLimit) {
            //file size limit is set and current counter has reached it.
            return false;
        }

        $this->incrementFilesCounter();
        $this->writeData($item->render());

        return true;
    }

    /**
     * Incrementing files count.
     */
    protected function incrementFilesCounter()
    {
        $this->countItems++;
    }

    /**
     * Incrementing file size.
     *
     * @param $data
     */
    protected function incrementSizeCounter($data)
    {
        $this->fileSize += mb_strlen($data);
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
     *
     * @throws \Exception
     */
    protected function writeData($data)
    {
        if (!$this->isOpened()) {
            throw new \Exception("Unable to add data to file, sitemap isn't opened.");
        }

        $this->incrementSizeCounter($data);
        $this->writeIntoHandler($data);
    }

    /**
     * Write sitemap XML dclaration.
     *
     * @throws \Exception
     */
    protected function writeDeclaration()
    {
        $this->writeData(static::DECLARATION);
    }

    /**
     * Write open root node tag.
     *
     * @throws \Exception
     */
    protected function openRootNode()
    {
        $this->writeData(sprintf('<%s %s>', static::ROOT_NODE_TAG, $this->namespaces()));
    }

    /**
     * Write close root node tag.
     *
     * @throws \Exception
     */
    protected function closeRootNode()
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