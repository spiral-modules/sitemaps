<?php

namespace Spiral\Tests\Sitemaps\Sitemaps;

use Spiral\Sitemaps\Items\URL;
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
        $sitemap1->addItem(new URL('location.com1'));
        $sitemap1->close();

        $sitemap2 = new Sitemap();
        $sitemap2->open($filename2);
        $sitemap2->addItem(new URL('location.com2'));
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
    }

    public function testCountOverflow()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';
        $filename1 = $this->app->directory('runtime') . 'sitemap1.xml';
        $filename2 = $this->app->directory('runtime') . 'sitemap2.xml';
        $filename3 = $this->app->directory('runtime') . 'sitemap3.xml';

        $sitemap1 = new Sitemap();
        $sitemap1->open($filename1);
        $sitemap1->addItem(new URL('location.com1'));
        $sitemap1->close();

        $sitemap2 = new Sitemap();
        $sitemap2->open($filename2);
        $sitemap2->addItem(new URL('location.com2'));
        $sitemap2->close();

        $sitemap3 = new Sitemap();
        $sitemap3->open($filename3);
        $sitemap3->addItem(new URL('location.com3'));
        $sitemap3->close();

        $index = new IndexSitemap(2);
        $index->open($filename);

        $this->assertTrue($index->addSitemap($sitemap1));
        $this->assertTrue($index->addSitemap($sitemap2));
        $this->assertFalse($index->addSitemap($sitemap3));
        $index->close();

        $this->assertFileExists($filename);

        $content = file_get_contents($filename);

        $this->assertContains($sitemap1->render(), $content);
        $this->assertContains($sitemap2->render(), $content);
        $this->assertNotContains($sitemap3->render(), $content);
    }

    /**
     * @expectedException \Spiral\Sitemaps\Exceptions\SitemapLogicException
     */
    public function testShouldOpenFirst()
    {
        $filename = $this->app->directory('runtime') . 'sitemap.xml';

        $sitemap = new Sitemap();
        $sitemap->open($filename);
        $sitemap->addItem(new URL('location.com'));
        $sitemap->close();

        $index = new IndexSitemap();
        $index->addSitemap($sitemap);
    }
}