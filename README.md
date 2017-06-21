# Sitemaps

Sitemaps generator tool for PHP
 
## Can do

1. Create sitemap file
2. Create sitemap index file

Also includes sitemap generator, that can handle multiple sitemap files automatically in case of files count or file size limits reached.

## Installation

Via composer

```
composer require spiral/sitemaps
```

## Usage

Below are some examples of how to use current package:

### Basic sitemap file generating

```
/**
 * Example 1.1, single sitemap via constructing.
 *
 * @param \Spiral\Sitemaps\Sitemaps\Sitemap $sitemap
 * @param \Spiral\Sitemaps\SitemapsConfig   $config
 */
public function create(SitemapsConfig $config)
{
    $sitemap = new \Spiral\Sitemaps\Sitemaps\Sitemap(
        ['video', 'image', 'alterlang'],
        $config->maxFiles('sitemap'),
        $config->maxFileSize('sitemap')
    );
    
    $sitemap->open('sitemap.xml');
    $sitemap->addItem(new PageItem('location.com'));
    $sitemap->close();
}
```
> First argument is namespaces array, can set namespaces if you're using additional content.<br/>
> If you pass `[]`, default one will be used.

```
/**
 * Example 1.2, single sitemap via DI container.
 * In this case no limits will be set, let's add them
 *
 * @param \Spiral\Sitemaps\Sitemaps\Sitemap $sitemap
 * @param \Spiral\Sitemaps\SitemapsConfig   $config
 */
public function create(Sitemap $sitemap, SitemapsConfig $config)
{
    $sitemap->setFilesCountLimit($config->maxFiles('sitemap'));
    $sitemap->setFileSizeLimit($config->maxFileSize('sitemap'));

    $sitemap->open('sitemap.xml');
    $sitemap->addItem(new PageItem('location.com'));
    $sitemap->close();
}
```


> You can set namespaces if you're using additional content, use it before you open the file

```
$item = new \Spiral\Sitemaps\Items\PageItem('location.com');
$item->addImage(new \Spiral\Sitemaps\Items\ImageItem('image-location.com'));
$item->addAlterLang(new \Spiral\Sitemaps\Items\AlterLangItem('de','location.de'));
$item->addVideo(new \Spiral\Sitemaps\Items\VideoItem('video-location.de'));

$sitemap->setNamespaces(['video', 'image', 'alterlang']);`
$sitemap->open('sitemap.xml');
$sitemap->addItem($item);
$sitemap->close();
```






To enable gzip
    //Pass compression ration to enable .gz encoding