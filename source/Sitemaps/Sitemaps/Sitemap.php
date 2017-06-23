<?php

namespace Spiral\Sitemaps\Sitemaps;

use Spiral\Sitemaps\Exceptions\AlreadyOpenedSitemapException;
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
        $this->setCompression($compression);

        if ($this->compressionEnabled()) {
            $filename .= '.gz';
        }

        parent::open($filename);
    }

    /**
     * @param int $fileSizeLimit
     */
    public function setFileSizeLimit(int $fileSizeLimit)
    {
        if ($this->isOpened()) {
            throw new AlreadyOpenedSitemapException(sprintf('Unable to set files count limit "%s".', $fileSizeLimit));
        }

        $this->fileSizeLimit = $fileSizeLimit;
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