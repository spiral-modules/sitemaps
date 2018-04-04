<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Entities;
use Spiral\Sitemaps\Namespaces;
use Spiral\Sitemaps\Validators\SitemapValidator;
use Spiral\Sitemaps\Writers\Patterns\URLPattern;

class Sitemap
{
//    public function __construct($builder)
//    {
//        $builder->start(); //in memory or tmp file
//        $builder->finish();
//        $builder->saveToFile();
//    }

    /** @var Entities\SitemapNamespace[] */
    protected $basicNamespaces = [];

    private $urlPattern;

    private $validator;

    public function __construct(Namespaces $nsManager, URLPattern $urlPattern, SitemapValidator $validator)
    {
        $this->urlPattern = $urlPattern;
        $this->validator = $validator;
        $this->basicNamespaces = $this->addNamespace($nsManager->getDefault());
    }

    public function start(\XMLWriter $writer, array $namespaces = [])
    {
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('urlset');

        $this->writeNamespaces($writer, $namespaces);
    }

    public function addURL(\XMLWriter $writer, Entities\URL $url)
    {
        //todo validate possibility of adding new element by urls amount and file size
        //if file size or items amount is out
        //  return false;
        //else
        //  write url
        //  return true;
        $this->urlPattern->write($writer, $url);

        return true;
    }

    /**
     * @param \XMLWriter $writer
     */
    public function end(\XMLWriter $writer)
    {
        $writer->endElement();
        $writer->endDocument();
    }

    /**
     * @param \XMLWriter $writer
     * @param array      $namespaces
     */
    protected function writeNamespaces(\XMLWriter $writer, array $namespaces)
    {
        foreach ($this->listNamespaces($namespaces) as $namespace) {
            $writer->writeAttribute($namespace->getName(), $namespace->getURI());
        }
    }

    /**
     * @param Entities\SitemapNamespace[] $namespaces
     *
     * @return array
     */
    protected function listNamespaces(array $namespaces): array
    {
        $result = $this->basicNamespaces;
        foreach ($namespaces as $namespace) {
            $result = $this->addNamespace($namespace, $result);
        }

        return $result;
    }

    /**
     * @param Entities\SitemapNamespace $namespace
     * @param array                     $namespaces
     *
     * @return array
     */
    protected function addNamespace(Entities\SitemapNamespace $namespace, array $namespaces = []): array
    {
        $namespaces[$namespace->getID()] = $namespace;

        return $namespaces;
    }
}