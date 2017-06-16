<?php

namespace Spiral\Sitemaps\Sitemaps;

use Spiral\Sitemaps\ItemInterface;

class PagesSitemap extends AbstractSitemap implements ItemInterface
{
    /**
     * File compression enabled?
     *
     * @var bool
     */
    protected $compress = false;

    /**
     * Add sitemap item.
     *
     * @param ItemInterface $item
     * @return int
     */
    public function addItem(ItemInterface $item): int
    {
        return $this->add($item);
    }

    /**
     * Change filename.
     *
     * @param string $filename
     * @return PagesSitemap
     */
    public function withFilename(string $filename): self
    {
        $clone = clone $this;
        $clone->filename = $filename;

        return $clone;
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
     * {@inheritdoc}
     *
     * @param string $filename
     * @param bool   $compress
     * @throws \Exception
     */
    public function open(string $filename, bool $compress = false)
    {
        $this->compress = $compress;
        if ($compress) {
            $filename .= '.gz';
        }

        parent::open($filename);
    }

    /**
     * {@inheritdoc}
     */
    protected function openHandler()
    {
        if ($this->compress) {
            $this->handler = gzopen($this->filename, 'wb');
        } else {
            $this->handler = fopen($this->filename, 'wb');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function writeIntoHandler($data): int
    {
        if ($this->compress) {
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
        if ($this->compress) {
            gzclose($this->handler);
        } else {
            fclose($this->handler);
        }
    }
}