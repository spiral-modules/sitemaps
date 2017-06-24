<?php

namespace Spiral\Tests\Sitemaps\Sitemaps;

use Spiral\Sitemaps\Items\PageItem;
use Spiral\Sitemaps\Sitemaps\IndexSitemap;
use Spiral\Sitemaps\Sitemaps\Sitemap;
use Spiral\Tests\BaseTest;

class IndexSitemapTest extends BaseTest
{
    public function testIndex()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';
        $filename1 = $this->app->directory('runtime') . 'sitemap1.xml';
        $filename2 = $this->app->directory('runtime') . 'sitemap2.xml';

        $sitemap1 = new Sitemap();
        $sitemap1->open($filename1);
        $sitemap1->addItem(new PageItem('location.com1'));
        $sitemap1->close();

        $sitemap2 = new Sitemap();
        $sitemap2->open($filename2);
        $sitemap2->addItem(new PageItem('location.com2'));
        $sitemap2->close();


        $index = new IndexSitemap();
        $index->open($filename);
        $index->addSitemap($sitemap1);
        $index->addSitemap($sitemap2);
        $index->close();

        $this->assertFileExists($filename);

        $content = file_get_contents($filename);
        $this->assertContains(IndexSitemap::DECLARATION, $content);
        $this->assertContains(IndexSitemap::DEFAULT_NS, $content);
        $this->assertContains('<' . IndexSitemap::ROOT_NODE_TAG, $content);
        $this->assertContains('</' . IndexSitemap::ROOT_NODE_TAG . '>', $content);
        $this->assertContains($sitemap1->render(), $content);
        $this->assertContains($sitemap2->render(), $content);

        /*
         * 1 test flow
         * 2 test size overflow
         * 2 test count overflow
         * 2 test setters
         * 2 test open first
         * 2 test already opened setters
         */
    }

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

    public function testCompression()
    {
        $filename1 = $this->app->directory('runtime') . 'sitemap.xml';
        $filename2 = $this->app->directory('runtime') . 'sitemap2.xml';
        $item = new PageItem('location.com');

        $sitemap = new Sitemap();
        $sitemap->open($filename1, true);
        $sitemap->addItem($item);
        $sitemap->close();

        $sitemap2 = new Sitemap();
        $sitemap2->open($filename2);
        $sitemap2->addItem($item);
        $sitemap2->close();

        $this->assertFileNotExists($filename1);
        $this->assertFileExists($filename1 . '.gz');

        $this->assertFileExists($filename2);
        $this->assertFileNotExists($filename2 . '.gz');

        $content1 = gzdecode(file_get_contents($filename1 . '.gz'));
        $content2 = file_get_contents($filename2);

        $this->assertEquals($content1, $content2);
    }

    public function testSetters()
    {
        $filename1 = $this->app->directory('runtime') . 'sitemap.xml';
        $filename2 = $this->app->directory('runtime') . 'sitemap2.xml';
        $item = new PageItem('location.com');

        $sitemap = new Sitemap(['video'], 500, 500);
        $sitemap->open($filename1);
        $sitemap->addItem($item);
        $sitemap->close();

        $sitemap2 = new Sitemap();
        $sitemap2->setNamespaces(['video']);
        $sitemap2->setFilesCountLimit(500);
        $sitemap2->setFileSizeLimit(500);

        $sitemap2->open($filename2);
        $sitemap2->addItem($item);
        $sitemap2->close();

        $this->assertFileExists($filename1);
        $this->assertFileExists($filename2);

        $content1 = file_get_contents($filename1);
        $content2 = file_get_contents($filename2);

        $this->assertEquals($content1, $content2);
    }

    /**
     * @expectedException \Spiral\Sitemaps\Exceptions\NotOpenedSitemapException
     */
    public function testShouldOpenFirst()
    {
        $sitemap = new Sitemap();
        $item = new PageItem('location.com');
        $sitemap->addItem($item);
    }

    /**
     * @expectedException \Spiral\Sitemaps\Exceptions\InvalidCompressionException
     */
    public function testFailedSetCompression()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap();
        $sitemap->open($filename, 10);
    }

    /**
     * @expectedException \Spiral\Sitemaps\Exceptions\AlreadyOpenedSitemapException
     */
    public function testFailedSetFilesCountLimit()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap();
        $sitemap->open($filename);

        $sitemap->setFilesCountLimit(10);
    }

    /**
     * @expectedException \Spiral\Sitemaps\Exceptions\AlreadyOpenedSitemapException
     */
    public function testFailedSetFileSizeLimit()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap();
        $sitemap->open($filename);

        $sitemap->setFileSizeLimit(10);
    }

    /**
     * @expectedException \Spiral\Sitemaps\Exceptions\AlreadyOpenedSitemapException
     */
    public function testFailedSetNamespaces()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap();
        $sitemap->open($filename);

        $sitemap->setNamespaces(['namespace']);
    }
}