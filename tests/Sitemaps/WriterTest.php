<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 28.03.2018
 * Time: 17:40
 */

namespace Spiral\Tests\Sitemaps;


use samdark\sitemap\DeflateWriter;
use Spiral\Sitemaps\Builders\MSitemap;
use Spiral\Sitemaps\Builders\Sitemap;
use Spiral\Sitemaps\Builders\SitemapIndex;
use Spiral\Sitemaps\Configs\TransportConfig;
use Spiral\Sitemaps\Elements;
use Spiral\Sitemaps\Exceptions\EnormousElementException;
use Spiral\Sitemaps\Namespaces;
use Spiral\Sitemaps\Reservation;
use Spiral\Sitemaps\SitemapsExceptionInterface;
use Spiral\Sitemaps\Transports\DeflateTransport;
use Spiral\Sitemaps\Transports\FileTransport;
use Spiral\Sitemaps\Transports\GZIPTransport;
use Spiral\Sitemaps\Validators\NamespaceValidator;
use Spiral\Sitemaps\Configurator;
use Spiral\Tests\BaseTest;

class WriterTest extends BaseTest
{
//    public function testValidator()
//    {
//        $validator = new NamespaceValidator();
//        echo PHP_EOL;
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
//    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    public function testReserve()
//    {
//        exit;
//        /** @var Reservation $reserve */
//        $reserve = $this->app->container->get(Reservation::class);
//
//        $reserve->calculateSize();
//        $reserve->calculateSize([$this->namespaces()->getByAlias('image')]);
//    }

    public function testMulti()
    {
        $url = new Elements\MultiLangURL([
            'ru' => 'http://loc.ru',
            'en' => 'http://loc.en',
            'fr' => 'http://loc.fr',
        ]);
        $url->addImage(new Elements\Image('http://loc..com/img.png'));
        $url->addImage(new Elements\Image('http://loc..ru/img.png'), 'ru');
        $url->addImage(new Elements\Image('http://loc..en/img.png'), 'en');
        $filename = 'alll.xml';
        try {
            /** @var MSitemap $builder */
            $builder = $this->container->get(MSitemap::class);
            $builder->start(new FileTransport(new TransportConfig()), $filename,[$this->namespaces()->getByAlias('lang'),$this->namespaces()->getByAlias('image')]);
            $builder->addURL($url);
            $builder->end();
        } catch (\Throwable $exception) {
            print_r('EX:' . $exception->getMessage() . ' [' . $exception->getFile() . '/' . $exception->getLine() . ']' . PHP_EOL);
        }
        print_r(file_get_contents($filename));
    }


    public function builderProvider()
    {
        return [
            [new FileTransport(new TransportConfig()), 'x1.xml'],
            [new GZIPTransport(new TransportConfig()), 'x2.xml'],
        ];
    }

    /**
     * @dataProvider builderProvider
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    public function testBuilderFile($transport, $filename)
//    {
//        try {
//            $builder = $this->builder();
//
//            $builder->start($transport, $filename);
//            if (!$builder->addURL(new Elements\URL('http://uri.loc', new \DateTime()))) {
//                print_r('ELEMENT TOO BIG, MAKE NEW FILE' . PHP_EOL);
//            } else {
//                print_r('ELEMENT ADDED' . PHP_EOL);
//            }
//
//            if (!$builder->addURL(new Elements\URL('http://uri.loc', new \DateTime(), 'weekly', .7))) {
//                print_r('ELEMENT TOO BIG, MAKE NEW FILE' . PHP_EOL);
//            } else {
//                print_r('ELEMENT ADDED' . PHP_EOL);
//            }
//
//            if (!$builder->addURL(new Elements\URL('http://uri.loc', new \DateTime(), 'weekly', .7))) {
//                print_r('ELEMENT TOO BIG, MAKE NEW FILE' . PHP_EOL);
//            } else {
//                print_r('ELEMENT ADDED' . PHP_EOL);
//            }
//
//            $builder->end();
//        } catch (SitemapsExceptionInterface $exception) {
//            print_r('EX:' . $exception->getMessage() . PHP_EOL);
//            print_r('EX1:' . get_class($exception) . PHP_EOL);
//        } catch (\Throwable $exception) {
//            print_r('EX2:' . $exception->getMessage() . PHP_EOL);
//        }
//
//        if (file_exists($filename)) {
//            print_r(file_get_contents($filename));
//        }
//        print_r(__METHOD__ . PHP_EOL);
//    }

    /**
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
//    public function testSitemapBuilder()
//    {
//        $filename = 'index.xml';
//        try {
//            $builder = $this->sitemapBuilder();
//            $builder->start(new FileTransport(new TransportConfig()), $filename);
//            if (!$builder->addSitemap(new Elements\Sitemap('http://uri.loc/x1.xml',
//                (new \DateTimeImmutable())->setTimestamp(filemtime('x1.xml'))))) {
//                print_r('ELEMENT TOO BIG, MAKE NEW FILE' . PHP_EOL);
//            } else {
//                print_r('ELEMENT ADDED' . PHP_EOL);
//            }
////
////            if (!$builder->addSitemap(new Elements\Sitemap('http://uri.loc/x2.xml.gz',
////                (new \DateTimeImmutable())->setTimestamp(filemtime('x2.xml.gz'))))) {
////                print_r('ELEMENT TOO BIG, MAKE NEW FILE' . PHP_EOL);
////            } else {
////                print_r('ELEMENT ADDED' . PHP_EOL);
////            }
//
//            $builder->end();
//        } catch (SitemapsExceptionInterface $exception) {
//            print_r('EX:' . $exception->getMessage() . PHP_EOL);
//        } catch (\Throwable $exception) {
//            print_r('EX2:' . $exception->getMessage() . PHP_EOL);
//        }
//
//        print_r(file_get_contents($filename));
//        print_r(__METHOD__ . PHP_EOL);
//    }

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
     * @return \Spiral\Sitemaps\Builders\SitemapIndex
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function sitemapBuilder(): SitemapIndex
    {
        return $this->app->container->get(SitemapIndex::class);
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
}