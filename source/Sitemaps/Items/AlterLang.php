<?php

namespace Spiral\Sitemaps\Items;

use Spiral\Sitemaps\Interfaces\SitemapItemInterface;

class AlterLang implements SitemapItemInterface
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

    /**
     * {@inheritdoc}
     */
    public function render(): string
    {
        return sprintf(
            '<xhtml:link rel="alternate" hreflang="%s" href="%s"/>',
            $this->hreflang,
            $this->href
        );
    }
}