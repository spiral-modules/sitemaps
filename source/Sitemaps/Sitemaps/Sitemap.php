<?php

namespace Spiral\Sitemaps\Sitemaps;

use Spiral\Sitemaps\Exceptions\InvalidCompressionException;
use Spiral\Sitemaps\ItemInterface;

class Sitemap extends AbstractSitemap implements ItemInterface
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
    protected $fileSizeLimit = null;

    /**
     * File size counter.
     *
     * @var int
     */
    protected $fileSize = 0;

    /**
     * Filename of current sitemap.
     *
     * @var null|string
     */
    protected $filename = null;

    /**
     * {@inheritdoc}
     * @param int|null $fileSizeLimit
     */
    public function __construct(array $namespaces = [], int $filesCountLimit = null, int $fileSizeLimit = null)
    {
        $this->fileSizeLimit = $fileSizeLimit;

        parent::__construct($namespaces, $filesCountLimit);
    }

    /**
     * Add sitemap item.
     *
     * @param ItemInterface $item
     *
     * @return bool
     */
    public function addItem(ItemInterface $item): bool
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
        $this->filename = $filename;
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
    protected function isFileSizeLimitReached($data): bool
    {
        return !empty($this->fileSizeLimit) && $this->fileSize + $this->calculateDataSize($data) >= $this->fileSizeLimit;
    }

    /**
     * Incrementing file size.
     *
     * @param $data
     */
    protected function incrementSizeCounter($data)
    {
        $this->fileSize += $this->calculateDataSize($data);
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
    protected function add(ItemInterface $item): bool
    {
        if ($this->isFileSizeLimitReached($item->render())) {
            return false;
        }

        return parent::add($item);
    }

    /**
     * {@inheritdoc}
     */
    protected function openHandler(string $filename)
    {
        if ($this->compressionEnabled()) {
            $this->handler = gzopen($filename, 'wb' . $this->compression);
        } else {
            $this->handler = fopen($filename, 'wb');
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