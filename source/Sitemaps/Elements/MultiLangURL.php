<?php

namespace Spiral\Sitemaps\Elements;

use Spiral\Sitemaps\ElementInterface;

class MultiLangURL implements ElementInterface
{
    /** @var Image[] */
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

        foreach ($locations as $lang => $location) {
            $this->alterLangs[] = new AlterLang($lang, $location);
        }
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
     * @return \Generator|\Spiral\Sitemaps\Elements\Location[]
     */
    public function getLocations()
    {
        return $this->locations;
//        foreach ($this->locations as $lang => $location) {
//            yield new Location($location, $this->locations);
//        }
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
     * @return array|Image[]
     */
    public function getImages(): array
    {
        return $this->images;
    }
}