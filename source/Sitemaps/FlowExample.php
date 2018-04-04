<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 27.03.2018
 * Time: 17:59
 */

namespace Spiral\Sitemaps;


class FlowExample
{
    public function __construct(\XMLWriter $writer)
    {
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString('    ');

        $writer->startDocument('1.0', 'UTF-8');

        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
        $writer->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        /////

        $writer->startElement('url');
        $writer->writeElement('loc', 'http://page.loc');
        $writer->writeElement('lastmod', (new \DateTime())->format('c'));
        $writer->writeElement('changefreq', 'weekly');
        $writer->writeElement('priority', 0.7);
        $writer->startElement('image:image');
        $writer->writeElement('image:loc', 'http://image.loc');

        $writer->startElement('image:caption');
        $writer->writeCData('caption');
        $writer->endElement(); //image:caption

        $writer->writeElement('image:geo_location', 'geo location');

        $writer->startElement('image:title');
        $writer->writeCData('title');
        $writer->endElement(); //image:title

        $writer->writeElement('image:license', 'license');

        $writer->endElement(); //image:image

        $writer->startElement('xhtml:link');
        $writer->writeAttribute('rel', 'alternate');
        $writer->writeAttribute('hreflang', 'ru');
        $writer->writeAttribute('href', 'http://page.ru');
        $writer->endElement(); //xhtml:link

        $writer->startElement('xhtml:link');
        $writer->writeAttribute('rel', 'alternate');
        $writer->writeAttribute('hreflang', 'uk');
        $writer->writeAttribute('href', 'http://page.uk');
        $writer->endElement(); //xhtml:link

        $writer->endElement(); //url

        /////

        $writer->endElement(); //urlset
        $writer->endDocument();
    }
}