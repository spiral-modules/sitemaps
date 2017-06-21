<?php

namespace Spiral;

use Spiral\Core\Service;
use Spiral\Files\FileManager;
use Spiral\Sitemaps\Items\ImageItem;
use Spiral\Sitemaps\Items\PageItem;
use Spiral\Sitemaps\Sitemaps;
use Spiral\Sitemaps\Sitemaps\Sitemap;
use Spiral\Sitemaps\SitemapsConfig;

class Example extends Service
{
    public function index(
        IndexSitemapWrapper $wrapper,
        SitemapWrapper $wrapper2,
        SitemapsConfig $config,
        FileManager $files
    ) {
        $sitemap = $this->sitemap($wrapper2, $config);
        $filename = 'sitemap.xml';
        $filename2 = 'sitemaps/1-sitemap.xml';
        $files->move($filename, $filename2);

        $index = new Sitemaps\IndexSitemap($wrapper, $config->maxFiles('index'));
        $index->open('sitemap.xml');
        $index->addSitemap($sitemap);
        $index->close();
    }

    public function sitemaps(
        SitemapsConfig $config,
        FileManager $files,
        IndexSitemapWrapper $wrapper,
        SitemapWrapper $wrapper2
    ) {
        $sitemaps = new Sitemaps($config, $files);
        $sitemaps->setIndexSitemapNamespaces([]);
        $sitemaps->setSitemapNamespaces(['video']);

        $sitemaps->open('sitemap.xml', '/');
        $sitemaps->addItem(new PageItem('loc'));
        $sitemaps->addItem(new PageItem('loc'));

        //Need to separate sitemap items in several standalone files
        $sitemaps->newSitemap();

        $sitemaps->addItem(new PageItem('loc'));

        $sitemaps->close();
    }
}

class SitemapExample
{
    /**
     * Example 1.1, single sitemap via DI container.
     * In this case no limits will be set, let's add them
     *
     * @param \Spiral\Sitemaps\Sitemaps\Sitemap $sitemap
     * @param \Spiral\Sitemaps\SitemapsConfig   $config
     */
    public function byContainer(Sitemap $sitemap, SitemapsConfig $config)
    {
        //In
        //optional parameters, default namespace will be added anyway
        $sitemap->setFilesCountLimit($config->maxFiles('sitemap'));
        $sitemap->setFileSizeLimit($config->maxFileSize('sitemap'));
        $sitemap->setNamespaces(['video', 'image', 'alterlang']);

        $item = new \Spiral\Sitemaps\Items\PageItem('location.com');
        $item->addImage(new \Spiral\Sitemaps\Items\ImageItem('image-location.com'));
        $item->addAlterLang(new \Spiral\Sitemaps\Items\AlterLangItem('de','location.de'));
        $item->addVideo(new \Spiral\Sitemaps\Items\VideoItem('video-location.de'));

        $sitemap = new \Spiral\Sitemaps\Sitemaps\Sitemap(
            ['video', 'image', 'alterlang'],
            $config->maxFiles('sitemap'),
        $config->maxFileSize('sitemap')
    );
        $sitemap->open('sitemap.xml', 8);
        $sitemap->addItem(new \Spiral\Sitemaps\Items\PageItem('location.com'));
        $sitemap->close();
    }

    /**
     * Example 1.2, single sitemap via constructing.
     *
     * @param SitemapsConfig $config
     */
    public function byConstructing(SitemapsConfig $config)
    {
        //optional parameters, default namespace will be added anyway
        $sitemap = new Sitemap(
            ['video', 'image', 'alterlang'],
            $config->maxFiles('sitemap'),
            $config->maxFileSize('sitemap')
        );

        $sitemap->open('sitemap.xml', 8);
        $sitemap->addItem(new PageItem('location.com'));
        $sitemap->close();
    }
}

class IndexSitemapExample
{
    /**
     * Example 2.1, index sitemap via DI container.
     *
     * @param Sitemaps\IndexSitemap $index
     * @param SitemapsConfig        $config
     */
    public function byContainer(Sitemaps\IndexSitemap $index, SitemapsConfig $config)
    {
        //optional parameters, default namespace will be added anyway
        $index->setFilesCountLimit($config->maxFiles('index'));
        $index->setNamespaces(['video', 'image', 'xmlns=""']);

        $index->open('sitemap.xml');
        $index->addSitemap(new Sitemap());
        $index->close();
    }

    /**
     * Example 2.2, index sitemap via constructing.
     *
     * @param SitemapsConfig $config
     */
    public function byConstructing(SitemapsConfig $config)
    {
        $index = new Sitemaps\IndexSitemap(
            ['video', 'image', 'xmlns=""'],
            $config->maxFiles('index')
        );

        $index->open('sitemap.xml');
        $index->addSitemap(new Sitemap());
        $index->close();
    }
}

class SitemapsExample
{
    public function __construct(Sitemaps $sitemaps)
    {
//        $sitemaps->
    }
}