<?php

namespace Spiral\Sitemaps\Builders;

use PHPUnit\Util\Xml;
use Spiral\Sitemaps\Entities\SitemapNamespace;
use Spiral\Sitemaps\Entities\URL;
use Spiral\Sitemaps\Namespaces;
use Spiral\Sitemaps\Writers\URLPattern;

class Sitemap
{
//    public function __construct($builder)
//    {
//        $builder->start(); //in memory or tmp file
//        $builder->finish();
//        $builder->saveToFile();
//    }

    /** @var array|SitemapNamespace[] */
    private $namespaces = [];

    private $urlPattern;

    public function __construct(Namespaces $nsManager, URLPattern $urlPattern)
    {
        $this->urlPattern = $urlPattern;

        $this->setNamespace($nsManager->getDefault());
    }

    public function setNamespace(SitemapNamespace $namespace)
    {
        $this->namespaces[$namespace->getID()] = $namespace;
    }

    public function open(\XMLWriter $writer)
    {
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('urlset');

        foreach ($this->namespaces as $namespace) {
            $writer->writeAttribute($namespace->getName(), $namespace->getURI());
        }
    }

    public function addURL(\XMLWriter $writer, URL $url)
    {
        $this->urlPattern->write($writer, $url);
    }

    /**
     * @param \XMLWriter $writer
     */
    public function close(\XMLWriter $writer)
    {
        $writer->endElement();
        $writer->endDocument();
    }
}