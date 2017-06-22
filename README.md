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

```php
/**
 * Constructor parameters are optional
 *
 * @param array $namespaces       List of namespaces to be added.
 *                                Default one will be added anyway, add other ones when adding urls with images, video, alter langs, etc.
 * @param int   $maxFilesLimit    Limit of urls added, if not set - no files count limit.
 * @param int   $maxFileSizeLimit Limit of uncompressed sitemap file size, if not set - no file size limit.
 */
$sitemap = new \Spiral\Sitemaps\Sitemaps\Sitemap(
    [
        'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"',
        'xmlns:xhtml="http://www.w3.org/1999/xhtml"'
    ],
    $config->maxFiles('sitemap'),
    $config->maxFileSize('sitemap')
);
//Or, just set parameters before opening sitemap:
$sitemap = new Sitemap();
$sitemap->setFilesCountLimit($config->maxFiles('sitemap'));
$sitemap->setFileSizeLimit($config->maxFileSize('sitemap'));
$sitemap->setNamespaces([
    'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"',
    'xmlns:xhtml="http://www.w3.org/1999/xhtml"'
]);
```
> `$config` is instance of `\Spiral\Sitemaps\SitemapsConfig` class, you can use numbers directly.

`\Spiral\Sitemaps\Namespaces` class is dedicated for using namespace aliases instead of passing full namespace strings:
```php
$sitemap->setNamespaces([
    'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"',
    'xmlns:xhtml="http://www.w3.org/1999/xhtml"'
]);
//Is equal to
$sitemap->setNamespaces($namespaces->get(['image', 'lang']));
```
> You can add your own aliases in config

Adding items to sitemap:

```php
$item = new \Spiral\Sitemaps\Items\PageItem('location.com');
$item->addImage(new \Spiral\Sitemaps\Items\ImageItem('image-location.com'));
$item->addAlterLang(new \Spiral\Sitemaps\Items\AlterLangItem('de', 'location.de'));
$item->addVideo(new \Spiral\Sitemaps\Items\VideoItem('video-location.com'));

$sitemap->open('sitemap.xml', 8);
$sitemap->addItem(new \Spiral\Sitemaps\Items\PageItem('location.com'));
$sitemap->close();
```

Output for

```php
$sitemap = new Sitemap(
    $namespaces->get(['image', 'lang']),
    $config->maxFiles('sitemap'),
    $config->maxFileSize('sitemap')
);

$item = new \Spiral\Sitemaps\Items\PageItem('location.com');
$item->addImage(new \Spiral\Sitemaps\Items\ImageItem('image-location.com'));
$item->addAlterLang(new \Spiral\Sitemaps\Items\AlterLangItem('de', 'location.de'));

$sitemap->open('sitemap.xml', 8);
$sitemap->addItem(new \Spiral\Sitemaps\Items\PageItem('location.com'));
$sitemap->close();
```
will be:

```xml


```
###todo xml output example
###todo video asset


### Sitemap Index file generating






To enable gzip
    //Pass compression ration to enable .gz encoding