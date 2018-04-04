<?php

namespace Spiral\Sitemaps\Writers\Patterns;

use Spiral\Sitemaps\Entities\Image;

/**
 * @link https://support.google.com/webmasters/answer/178636
 */
class ImagePattern
{
    private $writer;

    public function __construct(\XMLWriter $writer)
    {
        $this->writer = $writer;
    }

    public function write(Image $image)
    {
        $this->writer->startElement('image:image');

        $this->writeImageLocation($image);
        $this->writeImageCaption($image);
        $this->writeImageGeoLocation($image);
        $this->writeImageTitle($image);
        $this->writeImageLicense($image);

        $this->writer->endElement();
    }

    private function writeImageLocation(Image $image)
    {
        $this->writer->writeElement('image:loc', $image->getLocation());
    }

    private function writeImageCaption(Image $image)
    {
        if ($image->hasCaption()) {
            $this->writer->startElement('image:caption');
            $this->writer->writeCData($image->getCaption());
            $this->writer->endElement();
        }
    }

    private function writeImageGeoLocation(Image $image)
    {
        if ($image->hasGeoLocation()) {
            $this->writer->writeElement('image:geo_location', $image->hasGeoLocation());
        }
    }

    private function writeImageTitle(Image $image)
    {
        if ($image->hasTitle()) {
            $this->writer->startElement('image:title');
            $this->writer->writeCData($image->getTitle());
            $this->writer->endElement();
        }
    }

    private function writeImageLicense(Image $image)
    {
        if ($image->hasLicense()) {
            $this->writer->writeElement('image:license', $image->getLicense());
        }
    }
}