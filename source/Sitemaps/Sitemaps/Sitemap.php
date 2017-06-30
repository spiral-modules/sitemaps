<?php

namespace Spiral\Sitemaps\Sitemaps;

use Spiral\Sitemaps\Exceptions\DataOverflowException;
use Spiral\Sitemaps\Exceptions\InvalidCompressionException;
use Spiral\Sitemaps\Interfaces\SitemapInterface;
use Spiral\Sitemaps\Interfaces\SitemapItemInterface;

class Sitemap extends AbstractSitemap implements SitemapInterface
{
    const ROOT_NODE_TAG = 'urlset';

    /**
     * File compression enabled?
     *
     * @var int|null
     */
    protected $compression = null;

    /**
     * Allowed sitemap file limit in bytes, if set.
     *
     * @var null|int
     */
    protected $sizeLimit = null;

    /**
     * File size counter.
     *
     * @var int
     */
    protected $countSize = 0;

    /**
     * {@inheritdoc}
     * @param int|null $sizeLimit
     */
    public function __construct(array $namespaces = [], int $itemsLimit = null, int $sizeLimit = null)
    {
        $this->sizeLimit = $sizeLimit;
        $this->namespaces = $namespaces;
        $this->itemsLimit = $itemsLimit;
    }

    /**
     * Add sitemap item.
     *
     * @param SitemapItemInterface $item
     *
     * @return bool
     */
    public function addItem(SitemapItemInterface $item): bool
    {
        return $this->add($item);
    }

    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        $data = $this->loc() . $this->lastmod();

        return sprintf('<sitemap>%s</sitemap>', $data);
    }

    /**
     * {@inheritdoc}
     *
     * @param int|bool|null $compression
     */
    public function open(string $filename, $compression = null)
    {
        $this->setCompression($compression);
        if ($this->compressionEnabled()) {
            $filename .= '.gz';
        }

        parent::open($filename);
    }

    /**
     * Render location.
     *
     * @return string
     */
    protected function loc(): string
    {
        return sprintf('<loc>%s</loc>', $this->filename);
    }

    /**
     * Render last modification time.
     *
     * @return string
     */
    protected function lastmod(): string
    {
        $datetime = new \Datetime();

        return sprintf('<lastmod>%s</lastmod>', $datetime->format('c'));
    }

    /**
     * @param int|bool|null $compression
     */
    protected function setCompression($compression)
    {
        if ($compression === true) {
            $compression = 6;
        }

        $compression = (int)$compression;

        if (!empty($compression) && ($compression < 1 || $compression > 9)) {
            throw new InvalidCompressionException($compression);
        }

        $this->compression = $compression;
    }

    /**
     * Is compression enabled
     *
     * @return bool
     */
    protected function compressionEnabled(): bool
    {
        return (bool)$this->compression;
    }

    /**
     * Files count limit is set and current counter has reached it.
     *
     * @param $data
     *
     * @return bool
     */
    protected function isSizeLimitReached($data): bool
    {
        if (empty($this->sizeLimit)) {
            return false;
        }

        $size = $this->calculateDataSize($data);
        if ($this->countSize + $size > $this->sizeLimit) {
            if (empty($this->countItems)) {
                //Can't add event one item
                throw new DataOverflowException();
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * Incrementing file size.
     *
     * @param $data
     */
    protected function incrementSizeCounter($data)
    {
        $this->countSize += $this->calculateDataSize($data);
    }

    /**
     * @param $data
     *
     * @return int
     */
    protected function calculateDataSize($data)
    {
        return mb_strlen($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function writeData($data)
    {
        parent::writeData($data);

        $this->incrementSizeCounter($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function add(SitemapItemInterface $item): bool
    {
        if ($this->isSizeLimitReached($item->render())) {
            return false;
        }

        return parent::add($item);
    }

    /**
     * {@inheritdoc}
     */
    protected function openHandler()
    {
        if ($this->compressionEnabled()) {
            $this->handler = gzopen($this->filename, 'wb' . $this->compression);
        } else {
            $this->handler = fopen($this->filename, 'wb');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function writeIntoHandler($data): int
    {
        if ($this->compressionEnabled()) {
            return gzwrite($this->handler, $data);
        } else {
            return fwrite($this->handler, $data);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function closeHandler()
    {
        if ($this->compressionEnabled()) {
            gzclose($this->handler);
        } else {
            fclose($this->handler);
        }
    }
}