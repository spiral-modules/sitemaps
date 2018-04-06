<?php

namespace Spiral\Sitemaps\Patterns;

use Spiral\Sitemaps\Entities\AlterLang;
use Spiral\Sitemaps\EntityInterface;
use Spiral\Sitemaps\PatternInterface;

class AlterLangPattern
{
    /**
     * @param \XMLWriter                $writer
     * @param EntityInterface|AlterLang $lang
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