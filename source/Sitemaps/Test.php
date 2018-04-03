<?php

namespace Spiral\Sitemaps;

use Spiral\Sitemaps\Writer\Configurator;
use Spiral\Sitemaps\Writers\FileWriter;

class Test
{
    /*
     * file.xml - writes to the file directly. Ignores flush command
     * openMemory - writes to output var when flush
     */
    public function testFile(Configurator $configurator, FileWriter $writer)
    {
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString('    ');

        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('urlset');
        //writing elements
        //periodic flushing
        $writer->endElement();
        $writer->endDocument();
    }

    public function testMem(Configurator $configurator, \XMLWriter $writer)
    {
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString('    ');

        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('urlset');
        //writing elements
        //periodic flushing
        $writer->endElement();
        $writer->endDocument();
    }
}