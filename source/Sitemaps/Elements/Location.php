<?php

namespace Spiral\Sitemaps\Elements;

class Location
{
    private $location;
    private $alterLangs = [];

    public function __construct(string $location, array $alterLangs)
    {
        $this->location = $location;
        $this->alterLangs = $alterLangs;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return \Generator|\Spiral\Sitemaps\Elements\AlterLang[]
     */
    public function getAlterLangs()
    {
        foreach ($this->alterLangs as $lang => $location) {
            yield  new AlterLang($lang, $location);
        }
    }
}