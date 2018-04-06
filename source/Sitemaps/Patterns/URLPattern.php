<?php

namespace Spiral\Sitemaps\Patterns;

use Spiral\Sitemaps\Entities\URL;
use Spiral\Sitemaps\EntityInterface;
use Spiral\Sitemaps\PatternInterface;

class URLPattern
{
    /** @var ImagePattern  */
    private $images;

    /** @var AlterLangPattern  */
    private $langs;

    public function __construct(ImagePattern $images, AlterLangPattern $langs)
    {
        $this->images = $images;
        $this->langs = $langs;
    }

    /**
     * @param \XMLWriter          $writer
     * @param EntityInterface|URL $url
     */
    public function write(\XMLWriter $writer, EntityInterface $url)
    {
        $writer->startElement('url');
        $this->writeContent($writer, $url);
        $writer->endElement();
    }

    protected function writeContent(\XMLWriter $writer, $url)
    {
        $this->writeURL($writer, $url);
        $this->writeImages($writer, $url);
        $this->writeAlterLangs($writer, $url);
    }

    private function writeURL(\XMLWriter $writer, $url)
    {
        $this->writeLocation($writer, $url);
        $this->writeLastModificationTime($writer, $url);
        $this->writeChangeFrequency($writer, $url);
        $this->writePriority($writer, $url);
    }

    private function writeLocation(\XMLWriter $writer, URL $url)
    {
        $writer->writeElement('loc', $url->getLocation());
    }

    private function writeLastModificationTime(\XMLWriter $writer, URL $url)
    {
        if ($url->hasLastModificationTime()) {
            $writer->writeElement('lastmod', $url->getLastModificationTime()->format('c'));
        }
    }

    private function writeChangeFrequency(\XMLWriter $writer, URL $url)
    {
        if ($url->hasChangeFrequency()) {
            $writer->writeElement('changefreq', $url->getChangeFrequency());
        }
    }

    private function writePriority(\XMLWriter $writer, URL $url)
    {
        if ($url->hasPriority()) {
            $writer->writeElement('priority', number_format($url->getPriority(), 1));
        }
    }

    private function writeImages(\XMLWriter $writer, URL $url)
    {
        foreach ($url->getImages() as $image) {
            $this->images->write($writer, $image);
        }
    }

    private function writeAlterLangs(\XMLWriter $writer, URL $url)
    {
        foreach ($url->getAlterLangs() as $lang) {
            $this->langs->write($writer, $lang);
        }
    }
}