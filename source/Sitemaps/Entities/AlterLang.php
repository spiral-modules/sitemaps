<?php

namespace Spiral\Sitemaps\Entities;

use Spiral\Sitemaps\EntityInterface;

class AlterLang implements EntityInterface
{
    /** @var string */
    private $hreflang;

    /** @var string */
    private $href;

    /**
     * AlterLangItem constructor.
     *
     * @param string $hreflang
     * @param string $href
     */
    public function __construct(string $hreflang, string $href)
    {
        $this->hreflang = $hreflang;
        $this->href = $href;
    }

    public function getLang(): string
    {
        return $this->hreflang;
    }

    public function getLocation(): string
    {
        return $this->href;
    }
}