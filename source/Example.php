<?php

namespace Spiral;

use Spiral\Core\Service;
use Spiral\Files\FileManager;
use Spiral\Sitemaps\Items\Image;
use Spiral\Sitemaps\Items\URL;
use Spiral\Sitemaps\Namespaces;
use Spiral\Sitemaps\Sitemaps;
use Spiral\Sitemaps\Sitemaps\Sitemap;
use Spiral\Sitemaps\SitemapsConfig;

class SitemapExample
{
    /**
     * Example 1.1, single sitemap via DI container.
     * In this case no limits will be set, let's add them
     *
     * @param \Spiral\Sitemaps\Sitemaps\Sitemap $sitemap
     * @param \Spiral\Sitemaps\SitemapsConfig   $config
     * @param \Spiral\Sitemaps\Namespaces       $namespaces
     */
    public function example(Sitemap $sitemap, SitemapsConfig $config, Namespaces $namespaces)
    {
        //Constructor parameters are optional, default namespace will be added anyway
        /**
         * Constructor parameters are optional
         *
         * @param array $namespaces       List of namespaces to be added.
         *                                Default one will be added anyway, add other ones when adding urls with images, video, alter langs, etc.
         * @param int   $maxFilesLimit    Limit of urls added, if not set - no limit
         * @param int   $maxFileSizeLimit Limit of uncompressed sitemap file size, if not set - no limit
         */
        $sitemap = new Sitemap(
            [
                'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"',
                'xmlns:xhtml="http://www.w3.org/1999/xhtml"'
            ],
            $config->itemsLimit(),
            $config->sizeLimit()
        );

        //Or, just set parameters before opening sitemap
        $sitemap = new Sitemap();
        $sitemap->setFilesCountLimit($config->itemsLimit());
        $sitemap->setFileSizeLimit($config->sizeLimit());
        $sitemap->setNamespaces([
            'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"',
            'xmlns:xhtml="http://www.w3.org/1999/xhtml"'
        ]);

        //Namespaces class dedicated for using namespace aliases instead of passing full namespace strings, you can add your own aliases in config.
        $sitemap->setNamespaces([
            'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"',
            'xmlns:xhtml="http://www.w3.org/1999/xhtml"'
        ]);
        //Is equal to
        $sitemap->setNamespaces($namespaces->get(['image', 'lang']));

        $item = new \Spiral\Sitemaps\Items\URL('location.com');
        $item->addImage(new \Spiral\Sitemaps\Items\Image('image-location.com'));
        $item->addAlterLang(new \Spiral\Sitemaps\Items\AlterLang('de', 'location.de'));
        $item->addVideo(new \Spiral\Sitemaps\Items\Video('video-location.de'));

        $sitemap->open('sitemap.xml', 8);
        $sitemap->addItem(new \Spiral\Sitemaps\Items\URL('location.com'));
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
            $config->itemsLimit(),
            $config->sizeLimit()
        );

        $sitemap->open('sitemap.xml', 8);
        $sitemap->addItem(new URL('location.com'));
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
        $index->setFilesCountLimit($config->itemsLimit());
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
        $index = new \Spiral\Sitemaps\Sitemaps\IndexSitemap([], $config->itemsLimit());
        //or
        $index = new \Spiral\Sitemaps\Sitemaps\IndexSitemap();
        $index->setFilesCountLimit($config->itemsLimit());

        $index->open('sitemap.xml');
        $index->addSitemap(new \Spiral\Sitemaps\Sitemaps\Sitemap());
        $index->close();
    }
}

class SitemapsExample
{
    public function __construct(Sitemaps $sitemaps)
    {
    }
}