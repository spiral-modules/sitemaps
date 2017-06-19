<?php

namespace Spiral;

use Spiral\Core\Service;
use Spiral\Files\FileManager;
use Spiral\Sitemaps\Items\ImageItem;
use Spiral\Sitemaps\Items\PageItem;
use Spiral\Sitemaps\Sitemaps;
use Spiral\Sitemaps\Sitemaps\Sitemap;
use Spiral\Sitemaps\SitemapsConfig;
use Spiral\Sitemaps\Wrappers\IndexSitemapWrapper;
use Spiral\Sitemaps\Wrappers\SitemapWrapper;

class Example extends Service
{
    public function sitemap(SitemapWrapper $wrapper, SitemapsConfig $config)
    {
        $sitemap = new Sitemap($wrapper, $config->maxFiles('sitemap'), $config->maxFileSize('sitemap'));
        $sitemap->setWrapper($wrapper);

        $item = new PageItem('loc');
        $item->addImage(new ImageItem('loc1'));
        $item->addImage(new ImageItem('loc2'));

        $sitemap->open('sitemap.xml');
        $sitemap->addItem($item);
        $sitemap->close();

        return $sitemap;
    }

    public function index(IndexSitemapWrapper $wrapper, SitemapWrapper $wrapper2, SitemapsConfig $config, FileManager $files)
    {
        $sitemap = $this->sitemap($wrapper2, $config);
        $filename = 'sitemap.xml';
        $filename2 = 'sitemaps/1-sitemap.xml';
        $files->move($filename, $filename2);

        $index = new Sitemaps\IndexSitemap($wrapper, $config->maxFiles('index'));
        $index->open('sitemap.xml');
        $index->addSitemap($sitemap);
        $index->close();
    }

    public function sitemaps(SitemapsConfig $config, FileManager $files, IndexSitemapWrapper $wrapper, SitemapWrapper $wrapper2)
    {
        $sitemaps = new Sitemaps($config, $files);
        $sitemaps->setIndexSitemapWrapper($wrapper);
        $sitemaps->setSitemapWrapper($wrapper2);

        $sitemaps->open('sitemap.xml', '/');
        $sitemaps->addItem(new PageItem('loc'));
        $sitemaps->addItem(new PageItem('loc'));

        //Need to separate sitemap items in several standalone files
        $sitemaps->createNewSitemap();

        $sitemaps->addItem(new PageItem('loc'));

        $sitemaps->close();
    }
}