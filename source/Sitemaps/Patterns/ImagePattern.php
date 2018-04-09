<?php

namespace Spiral\Sitemaps\Patterns;

use Spiral\Sitemaps\Elements\Image;
use Spiral\Sitemaps\ElementInterface;
use Spiral\Sitemaps\PatternInterface;

/**
 * @link https://support.google.com/webmasters/answer/178636
 */
class ImagePattern implements PatternInterface
{
    /**
     * @param \XMLWriter             $writer
     * @param ElementInterface|Image $image
     */
    public function write(\XMLWriter $writer, Image $image)
    {
        $writer->startElement('image:image');

        $this->writeImageLocation($writer, $image);
        $this->writeImageCaption($writer, $image);
        $this->writeImageGeoLocation($writer, $image);
        $this->writeImageTitle($writer, $image);
        $this->writeImageLicense($writer, $image);

        $writer->endElement();
    }

    private function writeImageLocation(\XMLWriter $writer, Image $image)
    {
        $writer->writeElement('image:loc', $image->getLocation());
    }

    private function writeImageCaption(\XMLWriter $writer, Image $image)
    {
        if ($image->hasCaption()) {
            $writer->startElement('image:caption');
            $writer->writeCData($image->getCaption());
            $writer->endElement();
        }
    }

    private function writeImageGeoLocation(\XMLWriter $writer, Image $image)
    {
        if ($image->hasGeoLocation()) {
            $writer->writeElement('image:geo_location', $image->hasGeoLocation());
        }
    }

    private function writeImageTitle(\XMLWriter $writer, Image $image)
    {
        if ($image->hasTitle()) {
            $writer->startElement('image:title');
            $writer->writeCData($image->getTitle());
            $writer->endElement();
        }
    }

    private function writeImageLicense(\XMLWriter $writer, Image $image)
    {
        if ($image->hasLicense()) {
            $writer->writeElement('image:license', $image->getLicense());
        }
    }
}