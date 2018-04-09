<?php

namespace Spiral\Sitemaps\Elements;

use Spiral\Sitemaps\ElementInterface;

class Image implements ElementInterface
{
    /** @var string */
    private $loc;

    /** @var string */
    private $caption;

    /** @var string */
    private $geoLocation;

    /** @var string */
    private $title;

    /** @var string */
    private $license;

    /**
     * ImageItem constructor.
     *
     * @param string $loc
     * @param string $caption
     * @param string $geoLocation
     * @param string $title
     * @param string $license
     */
    public function __construct(
        string $loc,
        string $caption = null,
        string $geoLocation = null,
        string $title = null,
        string $license = null
    ) {
        $this->loc = $loc;
        $this->caption = $caption;
        $this->geoLocation = $geoLocation;
        $this->title = $title;
        $this->license = $license;
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
    public function hasCaption(): bool
    {
        return !empty($this->caption);
    }

    /**
     * @return string
     */
    public function getCaption(): string
    {
        return $this->caption;
    }

    /**
     * @return bool
     */
    public function hasGeoLocation(): bool
    {
        return !empty($this->geoLocation);
    }

    /**
     * @return string
     */
    public function getGeoLocation(): string
    {
        return $this->geoLocation;
    }

    /**
     * @return bool
     */
    public function hasTitle(): bool
    {
        return !empty($this->title);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return bool
     */
    public function hasLicense(): bool
    {
        return !empty($this->license);
    }

    /**
     * @return string
     */
    public function getLicense(): string
    {
        return $this->license;
    }
}