<?php

namespace Spiral\Sitemaps\Elements;

use Spiral\Sitemaps\ElementInterface;
use Spiral\Sitemaps\SitemapElementInterface;

class Sitemap implements ElementInterface, SitemapElementInterface
{
    /** @var string */
    private $loc;

    /** @var \DateTime */
    private $lastmod;

    /**
     * @param string             $loc
     * @param \DateTimeInterface $lastmod
     */
    public function __construct(string $loc, \DateTimeInterface $lastmod)
    {
        $this->loc = $loc;
        $this->lastmod = $lastmod;
    }

    public function write(\XMLWriter $writer)
    {
        $writer->startElement('sitemap');

        $writer->writeElement('loc', $this->getLocation());
        $writer->writeElement('lastmod', $this->getLastModificationTime()->format('c'));

        $writer->endElement();
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->loc;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getLastModificationTime(): \DateTimeInterface
    {
        return $this->lastmod;
    }
}