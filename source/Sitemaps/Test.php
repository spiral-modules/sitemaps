<?php

namespace Spiral\Sitemaps;

use Spiral\Sitemaps\Builders;
use Spiral\Sitemaps\Entities\URL;
use Spiral\Sitemaps\Writer;
use Spiral\Sitemaps\Writers;

class Test
{
    public function native(Writer\Configurator $configurator)
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        // or
        //$writer->openURI('uri.xml');
        $configurator->configure($writer);
    }

    public function file(Writers\FileWriter $writer, Builders\Sitemap $builder)
    {
        $writer->open('x.xml');
        $builder->start($writer);
        $builder->addURL($writer, new URL('uri.loc'));
        $builder->end($writer);
    }

    public function inMemory(Writers\InMemoryWriter $writer, Builders\Sitemap $builder)
    {
        $writer->open();
        $builder->start($writer);
        $builder->addURL($writer, new URL('uri.loc'));
        $builder->end($writer);
    }

    /*
     * file.xml - writes to the file directly. Ignores flush command
     * openMemory - writes to output var when flush
     */
    public function testFile(Writer\Configurator $configurator, Writers\FileWriter $writer)
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

    public function testMem(Writer\Configurator $configurator, \XMLWriter $writer)
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