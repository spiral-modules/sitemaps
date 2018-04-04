<?php

namespace Spiral\Sitemaps\Entities;

class Image
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

    public function getLocation(): string
    {
        return $this->loc;
    }

    public function hasCaption(): bool
    {
        return !empty($this->caption);
    }

    public function getCaption(): string
    {
        return $this->caption;
    }

    public function hasGeoLocation(): bool
    {
        return !empty($this->geoLocation);
    }

    public function getGeoLocation(): string
    {
        return $this->geoLocation;
    }

    public function hasTitle(): bool
    {
        return !empty($this->title);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function hasLicense(): bool
    {
        return !empty($this->license);
    }

    public function getLicense(): string
    {
        return $this->license;
    }
}