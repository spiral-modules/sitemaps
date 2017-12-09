<?php

namespace Spiral\Tests\Sitemaps\Sitemaps;

use Spiral\Sitemaps\Items\URL;
use Spiral\Sitemaps\Sitemaps\Sitemap;
use Spiral\Tests\BaseTest;

class SitemapTest extends BaseTest
{
    public function testSitemap()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap();
        $sitemap->open($filename);
        $item = new URL('location.com');
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

    public function testRender()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap();
        $sitemap->open($filename);
        $item = new URL('location.com');
        $sitemap->addItem($item);
        $sitemap->close();

        $this->assertFileExists($filename);

        $render = $sitemap->render();

        $this->assertNotContains(Sitemap::DECLARATION, $render);
        $this->assertNotContains(Sitemap::DEFAULT_NS, $render);
        $this->assertNotContains('<' . Sitemap::ROOT_NODE_TAG, $render);
        $this->assertNotContains('</' . Sitemap::ROOT_NODE_TAG . '>', $render);

        $this->assertContains('<loc>' . $filename . '</loc>', $render);
        $this->assertContains('<lastmod>', $render);
        $this->assertContains('</lastmod>', $render);
        $this->assertContains('<sitemap>', $render);
        $this->assertContains('</sitemap>', $render);
        $this->assertNotContains($item->render(), $render);
    }

    public function testSizeOverflow()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap([], null, 190);
        $sitemap->open($filename);

        $item1 = new URL('location.com1');
        $item2 = new URL('location.com2');
        $item3 = new URL('location.com3');
        $item4 = new URL('location.com4');

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

        $sitemap = new Sitemap([], 3);
        $sitemap->open($filename);

        $item1 = new URL('location.com1');
        $item2 = new URL('location.com2');
        $item3 = new URL('location.com3');
        $item4 = new URL('location.com4');

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
        $item = new URL('location.com');

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

    /**
     * @expectedException \Spiral\Sitemaps\Exceptions\SitemapLogicException
     */
    public function testShouldOpenFirst()
    {
        $sitemap = new Sitemap();
        $item = new URL('location.com');
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
}