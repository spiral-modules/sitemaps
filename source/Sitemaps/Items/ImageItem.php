<?php

namespace Spiral\Sitemaps\Items;

use Spiral\Sitemaps\Interfaces\SitemapItemInterface;

class ImageItem implements SitemapItemInterface
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
     * {@inheritdoc}
     */
    public function render(): string
    {
        $data = $this->loc() . $this->caption() . $this->geoLocation() . $this->title() . $this->license();

        return sprintf('<image:image>%s</image:image>', $data);
    }

    /**
     * Render location.
     *
     * @return string
     */
    protected function loc(): string
    {
        return sprintf('<image:loc>%s</image:loc>', $this->loc);
    }

    /**
     * Render caption.
     *
     * @return string
     */
    protected function caption(): string
    {
        if (!empty($this->caption)) {
            return sprintf('<image:caption><![CDATA[%s]]></image:caption>', $this->caption);
        }

        return '';
    }

    /**
     * Render geo location.
     *
     * @return string
     */
    protected function geoLocation(): string
    {
        if (!empty($this->geoLocation)) {
            return sprintf('<image:geo_location>%s</image:geo_location>', $this->geoLocation);
        }

        return '';
    }

    /**
     * Render title.
     *
     * @return string
     */
    protected function title(): string
    {
        if (!empty($this->title)) {
            return sprintf('<image:title><![CDATA[%s]]></image:title>', $this->title);
        }

        return '';
    }

    /**
     * Render license.
     *
     * @return string
     */
    protected function license(): string
    {
        if (!empty($this->license)) {
            return sprintf('<image:license>%s</image:license>', $this->license);
        }

        return '';
    }
}