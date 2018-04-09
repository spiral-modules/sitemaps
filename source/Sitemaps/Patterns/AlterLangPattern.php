<?php

namespace Spiral\Sitemaps\Patterns;

use Spiral\Sitemaps\Elements\AlterLang;
use Spiral\Sitemaps\ElementInterface;
use Spiral\Sitemaps\PatternInterface;

class AlterLangPattern implements PatternInterface
{
    /**
     * @param \XMLWriter                 $writer
     * @param ElementInterface|AlterLang $lang
     */
    public function write(\XMLWriter $writer, AlterLang $lang)
    {
        $writer->startElement('xhtml:link');

        $writer->writeAttribute('rel', 'alternate');
        $writer->writeAttribute('hreflang', $lang->getLang());
        $writer->writeAttribute('href', $lang->getLocation());

        $writer->endElement();
    }
}