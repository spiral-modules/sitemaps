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
    protected $compress = null;

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
     * @param string   $filename
     * @param int|null $compress
     *
     * @throws \Exception
     */
    public function open(string $filename, int $compress = null)
    {
        if ($compress === true) {
            $compress = 6;
        }

        if (!empty($compress) && ($compress < 1 || $compress > 9)) {
            throw new \OutOfRangeException(sprintf(
                'Unsupported compress value "%s". Valid values range is 1-9.',
                $compress
            ));
        }

        $this->compress = $compress;
        if (!empty($compress)) {
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
            $this->handler = gzopen($this->filename, 'wb' . $this->compress);
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