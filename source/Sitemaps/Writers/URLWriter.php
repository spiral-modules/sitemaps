<?php

namespace Spiral\Sitemaps\Writers;

use Spiral\Sitemaps\Items\URL;

class URLWriter
{
    private $writer;

    private $images;

    private $langs;

    public function __construct(ImageWriter $images, AlterLangWriter $langs, \XMLWriter $writer)
    {
        $this->writer = $writer;

        $this->images = new ImageWriter($writer);
        $this->langs = new AlterLangWriter($writer);
    }

    public function write($url)
    {
        $this->writer->startElement('url');

        $this->writeLocation($url);
        $this->writeLastModificationTime($url);
        $this->writeChangeFrequency($url);
        $this->writePriority($url);
        $this->writeImages($url);
        $this->writeAlterLangs($url);

        $this->writer->endElement();
    }

    private function writeLocation(URL $url)
    {
        $this->writer->writeElement('loc', $url->getLocation());
    }

    private function writeLastModificationTime(URL $url)
    {
        if ($url->hasLastModificationTime()) {
            $this->writer->writeElement('lastmod', $url->getLastModificationTime()->format('c'));
        }
    }

    private function writeChangeFrequency(URL $url)
    {
        if ($url->hasChangeFrequency()) {
            $this->writer->writeElement('changefreq', $url->getChangeFrequency());
        }
    }

    private function writePriority(URL $url)
    {
        if ($url->hasPriority()) {
            $this->writer->writeElement('priority', $url->getPriority());
        }
    }

    private function writeImages(URL $url)
    {
        foreach ($url->getImages() as $image) {
            $this->images->write($image);
        }
    }

    private function writeAlterLangs(URL $url)
    {
        foreach ($url->getAlterLangs() as $lang) {
            $this->langs->write($lang);
        }
    }
}