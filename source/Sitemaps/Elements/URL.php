<?php

namespace Spiral\Sitemaps\Elements;

use Spiral\Sitemaps\ElementInterface;

class URL implements ElementInterface
{
    /** @var Image[] */
    private $images = [];

    /** @var AlterLang[] */
    private $alterLangs = [];

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
     * Add alter lang item.
     *
     * @param AlterLang $lang
     *
     * @return $this
     */
    public function addAlterLang(AlterLang $lang)
    {
        $this->alterLangs[] = $lang;

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

    /**
     * @return array|AlterLang[]
     */
    public function getAlterLangs(): array
    {
        return $this->alterLangs;
    }
}