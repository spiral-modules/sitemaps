<?php

namespace Spiral\Sitemaps\Patterns;

use Spiral\Sitemaps\Elements\Location;
use Spiral\Sitemaps\Elements\MultiLangURL;
use Spiral\Sitemaps\Elements\URL;
use Spiral\Sitemaps\ElementInterface;
use Spiral\Sitemaps\PatternInterface;

class MultiLangURLPattern implements PatternInterface
{
    /** @var ImagePattern */
    private $images;

    /** @var AlterLangPattern */
    private $langs;

    public function __construct(ImagePattern $images, AlterLangPattern $langs)
    {
        $this->images = $images;
        $this->langs = $langs;
    }

    /**
     * @param \XMLWriter                                              $writer
     * @param ElementInterface|\Spiral\Sitemaps\Elements\MultiLangURL $url
     */
    public function write(\XMLWriter $writer, ElementInterface $url)
    {
        foreach ($url->getLocations() as $lang => $location) {
            $writer->startElement('url');
            $writer->writeElement('loc', $location);
            $this->writeLastModificationTime($writer, $url);
            $this->writeChangeFrequency($writer, $url);
            $this->writePriority($writer, $url);
            $this->writeImages($writer, $url);
            $this->writeAlterLangs($writer, $url);
            $writer->endElement();
        }
    }

    private function writeLocation(\XMLWriter $writer, Location $url)
    {
        $writer->writeElement('loc', $url->getLocation());
    }

    private function writeLastModificationTime(\XMLWriter $writer, MultiLangURL $url)
    {
        if ($url->hasLastModificationTime()) {
            $writer->writeElement('lastmod', $url->getLastModificationTime()->format('c'));
        }
    }

    private function writeChangeFrequency(\XMLWriter $writer, MultiLangURL $url)
    {
        if ($url->hasChangeFrequency()) {
            $writer->writeElement('changefreq', $url->getChangeFrequency());
        }
    }

    private function writePriority(\XMLWriter $writer, MultiLangURL $url)
    {
        if ($url->hasPriority()) {
            $writer->writeElement('priority', number_format($url->getPriority(), 1));
        }
    }

    private function writeImages(\XMLWriter $writer, MultiLangURL $url)
    {
        foreach ($url->getImages() as $image) {
            $this->images->write($writer, $image);
        }
    }

    private function writeAlterLangs(\XMLWriter $writer, MultiLangURL $url)
    {
        foreach ($url->getAlterLangs() as $lang) {
            $this->langs->write($writer, $lang);
        }
    }
}