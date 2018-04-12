<?php

namespace Spiral\Sitemaps\Elements;

use Spiral\Sitemaps\ElementInterface;

/**
 * @link https://support.google.com/webmasters/answer/178636
 */
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

    public function write(\XMLWriter $writer)
    {
        $writer->startElement('image:image');

        $this->writeImageLocation($writer);
        $this->writeImageCaption($writer);
        $this->writeImageGeoLocation($writer);
        $this->writeImageTitle($writer);
        $this->writeImageLicense($writer);

        $writer->endElement();
    }

    private function writeImageLocation(\XMLWriter $writer)
    {
        $writer->writeElement('image:loc', $this->getLocation());
    }

    private function writeImageCaption(\XMLWriter $writer)
    {
        if ($this->hasCaption()) {
            $writer->startElement('image:caption');
            $writer->writeCData($this->getCaption());
            $writer->endElement();
        }
    }

    private function writeImageGeoLocation(\XMLWriter $writer)
    {
        if ($this->hasGeoLocation()) {
            $writer->writeElement('image:geo_location', $this->getGeoLocation());
        }
    }

    private function writeImageTitle(\XMLWriter $writer)
    {
        if ($this->hasTitle()) {
            $writer->startElement('image:title');
            $writer->writeCData($this->getTitle());
            $writer->endElement();
        }
    }

    private function writeImageLicense(\XMLWriter $writer)
    {
        if ($this->hasLicense()) {
            $writer->writeElement('image:license', $this->getLicense());
        }
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