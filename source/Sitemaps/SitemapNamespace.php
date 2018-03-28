<?php

namespace Spiral\Sitemaps;

class SitemapNamespace
{
    const PREFIX = 'xmlns';

    private $name;
    private $uri;

    public function __construct(string $name = null, string $uri)
    {
        $this->name = $name;
        $this->uri = $uri;
    }

    public function getName(): string
    {
        if (empty($this->name)) {
            return self::PREFIX;
        }

        return self::PREFIX . ":$this->name";
    }

    public function getURI(): string
    {
        return $this->uri;
    }
}