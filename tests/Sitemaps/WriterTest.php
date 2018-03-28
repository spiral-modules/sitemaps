<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 28.03.2018
 * Time: 17:40
 */

namespace Spiral\Tests\Sitemaps;


use Spiral\Sitemaps\Writers\AlterLangWriter;
use Spiral\Sitemaps\Writers\ImageWriter;
use Spiral\Tests\BaseTest;

class WriterTest extends BaseTest
{
//    public function testWriter()
//    {
//        echo __METHOD__;
//        $xml = new \XMLWriter();
//        $xml->openMemory();
//        $xml->setIndent(true);
//        $xml->setIndentString('    ');
//
//        $xml->startDocument('1.0', 'UTF-8');
//
//        $xml->startElement('urlset');
//        $xml->writeAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
//        $xml->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
//        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
//
//        $writer = new \Spiral\Sitemaps\Writers\URLWriter(new ImageWriter($xml), new AlterLangWriter($xml), $xml);
//        $writer->write(new \Spiral\Sitemaps\Items\URL('bla.com'));
//
//        $xml->endElement(); //urlset
//        $xml->endDocument();
//
//        $mem = $xml->flush();
//        print_r([mb_strlen($mem), mb_strlen($mem, '8bit'), $mem]);
//    }

    public function testWriter2()
    {
        $writer = new \XMLWriter();
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

//        $flush = $writer->flush(false);
//        print_r([$flush, mb_strlen($flush)]);
        /////

        $writer->endElement(); //urlset
        $writer->endDocument();

        print_r([mb_strlen($writer->flush(true))]);
    }

    public function testWriter3()
    {
        $writer = new \XMLWriter();
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
        $flush = $writer->flush();
        print_r([PHP_EOL.$flush, mb_strlen($flush)]);
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

        $flush = $writer->flush();
        print_r([PHP_EOL.$flush, mb_strlen($flush)]);
        $writer->endElement(); //url

        /////

        $writer->endElement(); //urlset
        $writer->endDocument();

        $flush = $writer->flush();
        print_r([PHP_EOL.$flush, mb_strlen($flush)]);
    }
}