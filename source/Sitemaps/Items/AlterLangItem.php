<?php

namespace Spiral\Sitemaps\Items;

use Spiral\Sitemaps\ItemInterface;

/**
 * @link https://support.google.com/webmasters/answer/2620865
 */
class AlterLangItem implements ItemInterface
{
    /** @var string */
    private $hreflang;

    /** @var string */
    private $href;

    /**
     * ImageItem constructor.
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