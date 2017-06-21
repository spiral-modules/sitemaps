<?php

namespace Spiral\Sitemaps\Sitemaps;

use Spiral\Sitemaps\ItemInterface;

class Sitemap extends AbstractSitemap implements ItemInterface
{
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
     * @param string        $filename
     * @param int|bool|null $compression
     *
     * @throws \Exception
     */
    public function open(string $filename, $compression = null)
    {
        $this->handleCompression($compression);

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
            throw new \LogicException(sprintf(
                'Unable to set files count limit "%s" - sitemap is already opened.',
                $fileSizeLimit
            ));
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
    protected function handleCompression($compression)
    {
        if ($compression === true) {
            $compression = 6;
        }

        if (!empty($compression) && ($compression < 1 || $compression > 9)) {
            throw new \OutOfRangeException(sprintf(
                'Unsupported compress value "%s". Valid values range is 1-9.',
                $compression
            ));
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