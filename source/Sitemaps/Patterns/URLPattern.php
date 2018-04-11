<?php

namespace Spiral\Sitemaps\Patterns;

use Spiral\Sitemaps\Elements\URL;
use Spiral\Sitemaps\ElementInterface;
use Spiral\Sitemaps\PatternInterface;

class URLPattern implements PatternInterface
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
     * @param \XMLWriter           $writer
     * @param ElementInterface|URL $url
     */
    public function write(\XMLWriter $writer, ElementInterface $url)
    {
        $writer->startElement('url');
        $this->writeLocation($writer, $url);
        $this->writeLastModificationTime($writer, $url);
        $this->writeChangeFrequency($writer, $url);
        $this->writePriority($writer, $url);
        $this->writeSubContent($writer, $url);
        $writer->endElement();
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

    protected function writeSubContent(\XMLWriter $writer, URL $url)
    {
        $this->writeImages($writer, $url);
    }

    private function writeImages(\XMLWriter $writer, URL $url)
    {
        foreach ($url->getImages() as $image) {
            $this->images->write($writer, $image);
        }
    }
}