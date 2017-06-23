<?php

namespace Spiral\Tests\Sitemaps\Sitemaps;

use Spiral\Sitemaps\Items\PageItem;
use Spiral\Sitemaps\Sitemaps\IndexSitemap;
use Spiral\Sitemaps\Sitemaps\Sitemap;
use Spiral\Tests\BaseTest;

class SitemapTest extends BaseTest
{
    public function testRender()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap();
        $sitemap->open($filename);
        $item = new PageItem('location.com');
        $sitemap->addItem($item);
        $sitemap->close();

        $this->assertFileExists($filename);

        $content = file_get_contents($filename);

        $this->assertContains(Sitemap::DECLARATION, $content);
        $this->assertContains(Sitemap::DEFAULT_NS, $content);
        $this->assertContains('<' . Sitemap::ROOT_NODE_TAG, $content);
        $this->assertContains('</' . Sitemap::ROOT_NODE_TAG . '>', $content);
        $this->assertContains($item->render(), $content);
    }

//    public function testRenderForIndex()
//    {
//        $filename = $this->app->directory('runtime') . 'sitemap.xml';
//
//        $sitemap = new Sitemap();
//        $sitemap->open($filename);
//        $item = new PageItem('location.com');
//        $sitemap->addItem($item);
//        $sitemap->close();
//
//        $this->assertFileExists($filename);
//
//        $render = $sitemap->render();
//
//        $this->assertNotContains(Sitemap::DECLARATION, $render);
//        $this->assertNotContains(Sitemap::DEFAULT_NS, $render);
//
//        //todo render data
//
//        $this->assertContains('<' . IndexSitemap::ROOT_NODE_TAG, $render);
//        $this->assertContains('</' . IndexSitemap::ROOT_NODE_TAG . '>', $render);
//        $this->assertContains($item->render(), $render);
//    }

    public function testSizeOverflow()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap();
        $sitemap->setFileSizeLimit(190);
        $sitemap->open($filename);

        $item1 = new PageItem('location.com1');
        $item2 = new PageItem('location.com2');
        $item3 = new PageItem('location.com3');
        $item4 = new PageItem('location.com4');

        /*
         * <?xml version="1.0" encoding="UTF-8"?>
         * <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
         * <url><loc>location.com1</loc></url>
         * <url><loc>location.com2</loc></url>
         * <url><loc>location.com3</loc></url>
         * <url><loc>location.com4</loc></url>
         *
         * 2 items and sitemap header is about 203 bytes, so after only 2 items are added
         */
        $this->assertTrue($sitemap->addItem($item1));
        $this->assertTrue($sitemap->addItem($item2));
        $this->assertFalse($sitemap->addItem($item3));
        $this->assertFalse($sitemap->addItem($item4));
        $sitemap->close();

        $this->assertFileExists($filename);

        $content = file_get_contents($filename);

        $this->assertContains($item1->render(), $content);
        $this->assertContains($item2->render(), $content);
        $this->assertNotContains($item3->render(), $content);
        $this->assertNotContains($item4->render(), $content);
    }

    public function testCountOverflow()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap();
        $sitemap->setFilesCountLimit(3);
        $sitemap->open($filename);

        $item1 = new PageItem('location.com1');
        $item2 = new PageItem('location.com2');
        $item3 = new PageItem('location.com3');
        $item4 = new PageItem('location.com4');

        $this->assertTrue($sitemap->addItem($item1));
        $this->assertTrue($sitemap->addItem($item2));
        $this->assertTrue($sitemap->addItem($item3));
        $this->assertFalse($sitemap->addItem($item4));
        $sitemap->close();

        $this->assertFileExists($filename);

        $content = file_get_contents($filename);

        $this->assertContains($item1->render(), $content);
        $this->assertContains($item2->render(), $content);
        $this->assertContains($item3->render(), $content);
        $this->assertNotContains($item4->render(), $content);
    }

    /**
     * @expectedException
     */
    public function testSetters()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap();
        $sitemap->open($filename);

        $sitemap->setFilesCountLimit(10);
    }

    /*
     * 4 test setters after opening
     * 5 test compression
     */
}