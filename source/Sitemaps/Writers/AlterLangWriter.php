<?php

namespace Spiral\Sitemaps\Writers;

use Spiral\Sitemaps\Items\AlterLang;

class AlterLangWriter
{
    private $writer;

    public function __construct(\XMLWriter $writer)
    {
        $this->writer = $writer;
    }

    public function write(AlterLang $lang)
    {
        $this->writer->startElement('xhtml:link');

        $this->writer->writeAttribute('rel', 'alternate');
        $this->writer->writeAttribute('hreflang', $lang->getLang());
        $this->writer->writeAttribute('href', $lang->getLocation());

        $this->writer->endElement();
    }
}