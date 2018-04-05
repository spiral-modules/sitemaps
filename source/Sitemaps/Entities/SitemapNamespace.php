<?php

namespace Spiral\Sitemaps\Entities;

class SitemapNamespace
{
    const PREFIX = 'xmlns';

    /** @var string|null */
    private $name;

    /** @var string */
    private $uri;

    /**
     * @param string|null $name
     * @param string      $uri
     */
    public function __construct(string $name = null, string $uri)
    {
        $this->name = strtolower($name);
        $this->uri = strtolower($uri);
    }

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->getName() . '.' . $this->getURI();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        if (empty($this->name)) {
            return self::PREFIX;
        }

        if ($this->basename() === self::PREFIX) {
            return $this->basename();
        }

        return self::PREFIX . ':' . $this->basename();
    }

    /**
     * @return string
     */
    public function getURI(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    private function basename(): string
    {
        return preg_replace('/^' . self::PREFIX . ':/', '', $this->name);
    }
}