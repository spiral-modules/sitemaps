<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 28.03.2018
 * Time: 17:40
 */

namespace Spiral\Tests\Sitemaps;


use Spiral\Sitemaps\Builders\Sitemap;
use Spiral\Sitemaps\Entities;
use Spiral\Sitemaps\Namespaces;
use Spiral\Sitemaps\Validators\NamespaceValidator;
use Spiral\Sitemaps\Writer\Configurator;
use Spiral\Sitemaps\Writers\FileWriter;
use Spiral\Sitemaps\Writers\InMemoryWriter;
use Spiral\Sitemaps\Writers\PortionFileWriter;
use Spiral\Tests\BaseTest;

class WriterTest extends BaseTest
{
    public function testValidator()
    {
        $validator = new NamespaceValidator();
        echo PHP_EOL;
//        try { $validator->validate('xmlns:ab', 'uri'); } catch (\Throwable $e) { print_r($e->getMessage().PHP_EOL); }
//        try { $validator->validate('xmlns:ab', 'a:uri'); } catch (\Throwable $e) { print_r($e->getMessage().PHP_EOL); }
//        try { $validator->validate('xmlns:ab', 'a://uri'); } catch (\Throwable $e) { print_r($e->getMessage().PHP_EOL); }
//        try { $validator->validate('xmlns:ab', '0://uri'); } catch (\Throwable $e) { print_r($e->getMessage().PHP_EOL); }
//        try { $validator->validate('xmlns:ab', '0+://uri'); } catch (\Throwable $e) { print_r($e->getMessage().PHP_EOL); }
//        try { $validator->validate('xmlns:ab', '+://uri'); } catch (\Throwable $e) { print_r($e->getMessage().PHP_EOL); }
//        try { $validator->validate('xmlns:ab', 'a0://uri'); } catch (\Throwable $e) { print_r($e->getMessage().PHP_EOL); }
//        try { $validator->validate('xmlns:ab', 'a.0://uri'); } catch (\Throwable $e) { print_r($e->getMessage().PHP_EOL); }
//        try { $validator->validate('xmlns:ab', 'a+0://uri'); } catch (\Throwable $e) { print_r($e->getMessage().PHP_EOL); }
//        try { $validator->validate('xmlns:ab', 'a-0://uri'); } catch (\Throwable $e) { print_r($e->getMessage().PHP_EOL); }
    }

    /**
     * @return \Spiral\Sitemaps\Writer\Configurator
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function configurator(): Configurator
    {
        return $this->app->container->get(Configurator::class);
    }

    /**
     * @return \Spiral\Sitemaps\Writers\InMemoryWriter
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function inMemory(): InMemoryWriter
    {
        return $this->app->container->get(InMemoryWriter::class);
    }

    /**
     * @return \Spiral\Sitemaps\Writers\FileWriter
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function file(): FileWriter
    {
        return $this->app->container->get(FileWriter::class);
    }

    /**
     * @return \Spiral\Sitemaps\Writers\PortionFileWriter
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function portion(): PortionFileWriter
    {
        return $this->app->container->get(PortionFileWriter::class);
    }

    /**
     * @return \Spiral\Sitemaps\Builders\Sitemap
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function builder(): Sitemap
    {
        return $this->app->container->get(Sitemap::class);
    }

    /**
     * @return \Spiral\Sitemaps\Namespaces
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function namespaces(): Namespaces
    {
        return $this->app->container->get(Namespaces::class);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testFile()
    {
        $filename = 'x.xml';
        $builder = $this->builder();
        $writer = $this->file();
        $writer->open($filename);
//        try {
//            $writer->openURI($filename);
//        } catch (\Throwable $e) {
//            print_r($e->getMessage());
//            //throw  $e;
//        }
        $builder->start($writer);
//        print_r([$writer->flush(), file_get_contents($filename)]);

        $builder->addURL($writer, new Entities\URL('uri.loc', new \DateTime(), 'weekly', .7));
//        print_r([$writer->flush(), file_get_contents($filename)]);

//        exit;
        $builder->addURL($writer, new Entities\URL('uri.loc', new \DateTime(), 'weekly', .7));
        print_r([file_get_contents($filename),$writer->flush(), file_get_contents($filename)]);

//        $builder->end($writer);
//        print_r([$writer->flush(), file_get_contents($filename)]);
//        exit;

//        print_r(file_get_contents($filename));
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testPortion()
    {
        exit;
        $filename = 'x.xml';
        $builder = $this->builder();
        $writer = $this->portion();
        $writer->open($filename);
        try {
            $writer->openURI($filename);
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            //throw  $e;
        }
        $builder->start($writer);
        $builder->addURL($writer->writer(), new Entities\URL('uri.loc', new \DateTime(), 'weekly', .7));
        $builder->end($writer);

        print_r(file_get_contents($filename));
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Spiral\Sitemaps\Exceptions\InvalidNamespaceException
     */
    public function testInMemory()
    {
        exit;
        $builder = $this->builder();
        $writer = $this->inMemory();
        $writer->open();
        $builder->start($writer,
            [
                new Entities\SitemapNamespace('bla', 'bla.loc'),
                $this->namespaces()->getByAlias('image'),
                $this->namespaces()->get('bla2', 'http://blabla.loc'),
                $this->namespaces()->get('xmlns:a2', '//blabla.loc'),
            ]);
        $builder->addURL($writer, new Entities\URL('uri.loc', new \DateTime(), 'weekly', .7));
        $builder->end($writer);

        //print_r($writer->close()->getContent());
        exit;

//        $writer = $this->inMemory();
//        $writer->open();

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

//        print_r([
//            $writer->flush(false),
//            $writer->flush(false),
//            $writer->flush(true),
//            $writer->flush(false)
//        ]);

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

        echo($writer->close());
    }

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

//    public function testWriter2()
//    {
//        $writer = new \XMLWriter();
//        $writer->openMemory();
//        $writer->setIndent(true);
//        $writer->setIndentString('    ');
//
//        $writer->startDocument('1.0', 'UTF-8');
//
//        $writer->startElement('urlset');
//        $writer->writeAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
//        $writer->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
//        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
//
//        /////
//
//        $writer->startElement('url');
//        $writer->writeElement('loc', 'http://page.loc');
//        $writer->writeElement('lastmod', (new \DateTime())->format('c'));
//        $writer->writeElement('changefreq', 'weekly');
//        $writer->writeElement('priority', 0.7);
//
//        $writer->startElement('image:image');
//        $writer->writeElement('image:loc', 'http://image.loc');
//
//        $writer->startElement('image:caption');
//        $writer->writeCData('caption');
//        $writer->endElement(); //image:caption
//
//        $writer->writeElement('image:geo_location', 'geo location');
//
//        $writer->startElement('image:title');
//        $writer->writeCData('title');
//        $writer->endElement(); //image:title
//
//        $writer->writeElement('image:license', 'license');
//
//        $writer->endElement(); //image:image
//
//        $writer->startElement('xhtml:link');
//        $writer->writeAttribute('rel', 'alternate');
//        $writer->writeAttribute('hreflang', 'ru');
//        $writer->writeAttribute('href', 'http://page.ru');
//        $writer->endElement(); //xhtml:link
//
//        $writer->startElement('xhtml:link');
//        $writer->writeAttribute('rel', 'alternate');
//        $writer->writeAttribute('hreflang', 'uk');
//        $writer->writeAttribute('href', 'http://page.uk');
//        $writer->endElement(); //xhtml:link
//
//        $writer->endElement(); //url
//
////        $flush = $writer->flush(false);
////        print_r([$flush, mb_strlen($flush)]);
//        /////
//
//        $writer->endElement(); //urlset
//        $writer->endDocument();
//
//        print_r([mb_strlen($writer->flush(true))]);
//    }

//    public function testWriter3()
//    {
//        $writer = new \XMLWriter();
//        $writer->openMemory();
//        $writer->setIndent(true);
//        $writer->setIndentString('    ');
//
//        $writer->startDocument('1.0', 'UTF-8');
//
//        $writer->startElement('urlset');
//        $writer->writeAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
//        $writer->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
//        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
//
//        /////
//
//        $writer->startElement('url');
//        $flush = $writer->flush();
//        print_r([PHP_EOL.$flush, mb_strlen($flush)]);
//        $writer->writeElement('loc', 'http://page.loc');
//        $writer->writeElement('lastmod', (new \DateTime())->format('c'));
//        $writer->writeElement('changefreq', 'weekly');
//        $writer->writeElement('priority', 0.7);
//
//        $writer->startElement('image:image');
//        $writer->writeElement('image:loc', 'http://image.loc');
//
//        $writer->startElement('image:caption');
//        $writer->writeCData('caption');
//        $writer->endElement(); //image:caption
//
//        $writer->writeElement('image:geo_location', 'geo location');
//
//        $writer->startElement('image:title');
//        $writer->writeCData('title');
//        $writer->endElement(); //image:title
//
//        $writer->writeElement('image:license', 'license');
//
//        $writer->endElement(); //image:image
//
//        $writer->startElement('xhtml:link');
//        $writer->writeAttribute('rel', 'alternate');
//        $writer->writeAttribute('hreflang', 'ru');
//        $writer->writeAttribute('href', 'http://page.ru');
//        $writer->endElement(); //xhtml:link
//
//        $writer->startElement('xhtml:link');
//        $writer->writeAttribute('rel', 'alternate');
//        $writer->writeAttribute('hreflang', 'uk');
//        $writer->writeAttribute('href', 'http://page.uk');
//        $writer->endElement(); //xhtml:link
//
//        $flush = $writer->flush();
//        print_r([PHP_EOL.$flush, mb_strlen($flush)]);
//        $writer->endElement(); //url
//
//        /////
//
//        $writer->endElement(); //urlset
//        $writer->endDocument();
//
//        $flush = $writer->flush();
//        print_r([PHP_EOL.$flush, mb_strlen($flush)]);
//    }
}