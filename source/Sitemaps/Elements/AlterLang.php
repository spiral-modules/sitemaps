<?php

namespace Spiral\Sitemaps\Elements;

use Spiral\Sitemaps\ElementInterface;

/**
 * @link https://support.google.com/webmasters/answer/2620865
 */
class AlterLang implements ElementInterface
{
    /** @var string */
    private $lang;

    /** @var string */
    private $location;

    /**
     * AlterLangItem constructor.
     *
     * @param string $lang
     * @param string $location
     */
    public function __construct(string $lang, string $location)
    {
        $this->lang = $lang;
        $this->location = $location;
    }

    public function write(\XMLWriter $writer)
    {
        $writer->startElement('xhtml:link');

        $writer->writeAttribute('rel', 'alternate');
        $writer->writeAttribute('hreflang', $this->getLang());
        $writer->writeAttribute('href', $this->getLocation());

        $writer->endElement();
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }
}