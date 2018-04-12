<?php

namespace Spiral\Sitemaps\Elements;

use Spiral\Sitemaps\ElementInterface;
use Spiral\Sitemaps\SitemapElementInterface;

class URL implements ElementInterface, SitemapElementInterface
{
    /** @var Image[] */
    private $images = [];

    /** @var string */
    private $loc;

    /** @var \DateTimeInterface|null */
    private $lastmod;

    /** @var null|string */
    private $changefreq;

    /** @var float|null */
    private $priority;

    /**
     * PageItem constructor.
     *
     * @param string                  $loc
     * @param \DateTimeInterface|null $lastmod
     * @param string|null             $changefreq
     * @param float|null              $priority
     */
    public function __construct(
        string $loc,
        \DateTimeInterface $lastmod = null,
        string $changefreq = null,
        float $priority = null
    ) {
        $this->loc = $loc;
        $this->lastmod = $lastmod;
        $this->changefreq = strtolower($changefreq);
        $this->priority = $priority;
    }

    /**
     * @param \XMLWriter $writer
     */
    public function write(\XMLWriter $writer)
    {
        $writer->startElement('url');
        $this->writeLocation($writer);
        $this->writeLastModificationTime($writer);
        $this->writeChangeFrequency($writer);
        $this->writePriority($writer);
        $this->writeImages($writer);
        $writer->endElement();
    }

    private function writeLocation(\XMLWriter $writer)
    {
        $writer->writeElement('loc', $this->getLocation());
    }

    private function writeLastModificationTime(\XMLWriter $writer)
    {
        if ($this->hasLastModificationTime()) {
            $writer->writeElement('lastmod', $this->getLastModificationTime()->format('c'));
        }
    }

    private function writeChangeFrequency(\XMLWriter $writer)
    {
        if ($this->hasChangeFrequency()) {
            $writer->writeElement('changefreq', $this->getChangeFrequency());
        }
    }

    private function writePriority(\XMLWriter $writer)
    {
        if ($this->hasPriority()) {
            $writer->writeElement('priority', number_format($this->getPriority(), 1));
        }
    }

    private function writeImages(\XMLWriter $writer)
    {
        foreach ($this->getImages() as $image) {
            $image->write($writer);
        }
    }

    /**
     * Add image item.
     *
     * @param Image $image
     *
     * @return $this
     */
    public function addImage(Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->loc;
    }

    /**
     * @return bool
     */
    public function hasLastModificationTime(): bool
    {
        return !empty($this->lastmod);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getLastModificationTime(): \DateTimeInterface
    {
        return $this->lastmod;
    }

    /**
     * @return bool
     */
    public function hasChangeFrequency(): bool
    {
        return !empty($this->changefreq);
    }

    /**
     * @return string
     */
    public function getChangeFrequency(): string
    {
        return $this->changefreq;
    }

    /**
     * @return bool
     */
    public function hasPriority(): bool
    {
        return !empty($this->priority);
    }

    /**
     * @return float
     */
    public function getPriority(): float
    {
        return $this->priority;
    }

    /**
     * @return array|Image[]
     */
    public function getImages(): array
    {
        return $this->images;
    }
}