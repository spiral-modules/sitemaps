<?php

namespace Spiral\Sitemaps\Sitemaps;

use Spiral\Files\FileManager;
use Spiral\Sitemaps\ItemInterface;
use Spiral\Sitemaps\SitemapInterface;
use Spiral\Sitemaps\WrapperInterface;

/**
 * @link https://www.sitemaps.org/ru/protocol.html
 */
abstract class AbstractSitemap implements SitemapInterface
{
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

    /** @var WrapperInterface */
    protected $wrapper;

    /** @var int */
    protected $fileSize = 0;

    /** @var int */
    protected $countItems = 0;

    /**
     * AbstractSitemap constructor.
     *
     * @param WrapperInterface $wrapper
     */
    public function __construct(WrapperInterface $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    /**
     * Add sitemap item.
     *
     * @param ItemInterface $item
     *
     * @return int
     * @throws \Exception
     */
    protected function add(ItemInterface $item): int
    {
        $this->countItems++;

        return $this->writeData($item->render());
    }

    /**
     * {@inheritdoc}
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsCount(): int
    {
        return $this->countItems;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilename(): string
    {
        return $this->filename;
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
     * Open file.
     *
     * @param string $filename
     *
     * @throws \Exception
     */
    public function open(string $filename)
    {
        $this->filename = $filename;

        $this->openHandler();

        if (!$this->isOpened()) {
            throw new \Exception('Error during opening/creating file.');
        }

        $this->writeData($this->wrapper->header());
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
     * @return int
     */
    protected function writeData($data): int
    {
        if (!$this->isOpened()) {
            throw new \Exception("Unable to add data to file, file isn't opened.");
        }

        $this->fileSize += mb_strlen($data);

        return $this->writeIntoHandler($data);
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
     * Close file.
     */
    public function close()
    {
        if ($this->isOpened()) {
            $this->writeData($this->wrapper->footer());
            $this->closeHandler();
        }

        $this->handler = null;
    }

    /**
     * Close file handler.
     */
    protected function closeHandler()
    {
        fclose($this->handler);
    }

    /**
     * Destructing.
     */
    public function __destruct()
    {
        $this->close();
    }
}