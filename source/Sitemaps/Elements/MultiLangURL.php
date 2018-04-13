<?php

namespace Spiral\Sitemaps\Elements;

use Spiral\Sitemaps\ElementInterface;

/*
 * 1 pass [en: loc.en, ru: loc.ru]
 * 2 pass image new Image(), optional $lang
 */

class MultiLangURL implements ElementInterface, SitemapElementInterface
{
    /** @var array */
    private $images = [];

    /** @var \Spiral\Sitemaps\Elements\AlterLang[] */
    private $alterLangs = [];

    /** @var array */
    private $locations;

    /** @var \DateTimeInterface|null */
    private $lastmod;

    /** @var null|string */
    private $changefreq;

    /** @var float|null */
    private $priority;

    const DEFAULT_IMAGE_LANG = null;

    /**
     * PageItem constructor.
     *
     * @param array                   $locations
     * @param \DateTimeInterface|null $lastmod
     * @param string|null             $changefreq
     * @param float|null              $priority
     */
    public function __construct(
        array $locations,
        \DateTimeInterface $lastmod = null,
        string $changefreq = null,
        float $priority = null
    ) {
        $this->locations = $locations;
        $this->lastmod = $lastmod;
        $this->changefreq = strtolower($changefreq);
        $this->priority = $priority;

        foreach ($locations as $lang => $location) {
            $this->alterLangs[] = new AlterLang($lang, $location);
        }
    }

    /**
     * Add image item.
     *
     * @param Image       $image
     * @param string|null $lang
     *
     * @return $this
     */
    public function addImage(Image $image, string $lang = self::DEFAULT_IMAGE_LANG)
    {
        $this->images[$lang][] = $image;

        return $this;
    }

    /**
     * @return \Generator|\Spiral\Sitemaps\Elements\Location[]
     */
    public function getLocations()
    {
        return $this->locations;
    }

    public function getAlterLangs()
    {
        return $this->alterLangs;
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
     * @param string $lang
     *
     * @return array|Image[]
     */
    public function getImages(string $lang): array
    {
        return array_merge($this->defaultImages(),$this->langImages($lang));
    }

    private function defaultImages()
    {
        return $this->images[self::DEFAULT_IMAGE_LANG];
    }

    private function langImages(string $lang)
    {
        return $this->images[$lang] ?? [];
    }

    public function write(\XMLWriter $writer)
    {
        foreach ($this->getLocations() as $lang => $location) {
            $writer->startElement('url');
            $writer->writeElement('loc', $location);
            $this->writeLastModificationTime($writer);
            $this->writeChangeFrequency($writer);
            $this->writePriority($writer);
            $this->writeImages($writer, $lang);
            $this->writeAlterLangs($writer);
            $writer->endElement();
        }
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

    private function writeImages(\XMLWriter $writer, string $lang)
    {
        foreach ($this->getImages($lang) as $image) {
            $image->write($writer);
        }
    }

    private function writeAlterLangs(\XMLWriter $writer)
    {
        foreach ($this->getAlterLangs() as $lang) {
            $lang->write($writer);
        }
    }
}