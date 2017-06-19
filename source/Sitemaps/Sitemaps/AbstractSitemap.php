<?php

namespace Spiral\Sitemaps\Sitemaps;

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

    /**
     * XML wrapper.
     *
     * @var null|WrapperInterface
     */
    protected $wrapper;

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
     * @param null|WrapperInterface $wrapper
     * @param null|int              $filesCountLimit
     * @param null|int              $fileSizeLimit
     */
    public function __construct(WrapperInterface $wrapper = null, int $filesCountLimit = null, int $fileSizeLimit = null)
    {
        $this->wrapper = $wrapper;
        $this->filesCountLimit = $filesCountLimit;
        $this->fileSizeLimit = $fileSizeLimit;
    }

    /**
     * Set new wrapper. File should not be opened by that time.
     *
     * @param WrapperInterface $wrapper
     */
    public function setWrapper(WrapperInterface $wrapper)
    {
        if ($this->isOpened()) {
            throw new \LogicException(sprintf('Unable to set wrapper "%s" - sitemap is already opened.', get_class($wrapper)));
        }

        $this->wrapper = $wrapper;
    }

    /**
     * @param int $filesCountLimit
     */
    public function setFilesCountLimit(int $filesCountLimit)
    {
        if ($this->isOpened()) {
            throw new \LogicException(sprintf('Unable to set files count limit "%s" - sitemap is already opened.', $filesCountLimit));
        }

        $this->filesCountLimit = $filesCountLimit;
    }

    /**
     * @param int $fileSizeLimit
     */
    public function setFileSizeLimit(int $fileSizeLimit)
    {
        if ($this->isOpened()) {
            throw new \LogicException(sprintf('Unable to set files count limit "%s" - sitemap is already opened.', $fileSizeLimit));
        }

        $this->fileSizeLimit = $fileSizeLimit;
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
     * Open file.
     *
     * @param string $filename
     *
     * @throws \Exception
     */
    public function open(string $filename)
    {
        if ($this->isOpened()) {
            //already opened.
            return;
        }

        if (empty($this->wrapper)) {
            throw new \LogicException("Wrapper should be set first.");
        }

        $this->filename = $filename;

        $this->openHandler();
        $this->writeData($this->wrapper->header());
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
}