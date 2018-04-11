<?php

namespace Spiral\Sitemaps\Elements;

use Spiral\Sitemaps\ElementInterface;

/**
 * @link https://support.google.com/webmasters/answer/2620865
 */
class AlterLang implements ElementInterface
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

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->hreflang;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->href;
    }
}