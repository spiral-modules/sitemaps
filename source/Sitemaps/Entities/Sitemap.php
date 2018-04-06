<?php

namespace Spiral\Sitemaps\Entities;

use Spiral\Sitemaps\EntityInterface;

class Sitemap implements EntityInterface
{
    /** @var string */
    private $loc;

    /** @var \DateTime */
    private $lastmod;

    /**
     * @param string $loc
     * @param string $filename
     */
    public function __construct(string $loc, string $filename)
    {
        $this->loc = $loc;
        $this->lastmod =  (new \DateTimeImmutable())->setTimestamp(filemtime($filename));
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