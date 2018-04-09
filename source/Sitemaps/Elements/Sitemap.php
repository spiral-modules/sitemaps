<?php

namespace Spiral\Sitemaps\Elements;

use Spiral\Sitemaps\ElementInterface;

class Sitemap implements ElementInterface
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