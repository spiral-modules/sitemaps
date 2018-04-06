<?php

namespace Spiral\Sitemaps;

use Spiral\Sitemaps\Configs\DeclarationIndexConfig;

class DeclarationIndex implements DeclarationInterface
{
    /** @var DeclarationIndexConfig */
    private $config;

    /** @var Namespaces */
    private $namespaces;

    /**
     * Declaration constructor.
     *
     * @param DeclarationIndexConfig $config
     * @param Namespaces        $namespaces
     */
    public function __construct(DeclarationIndexConfig $config, Namespaces $namespaces)
    {
        $this->config = $config;
        $this->namespaces = $namespaces;
    }

    /**
     * @param \XMLWriter $writer
     * @param array      $namespaces
     */
    public function declare(\XMLWriter $writer, array $namespaces = [])
    {
        $writer->startDocument($this->config->version(), $this->config->encoding());
        $writer->startElement($this->config->rootElement());

        $this->writeNamespaces($writer, $namespaces);
    }

    /**
     * @param \XMLWriter $writer
     */
    public function finalize(\XMLWriter $writer)
    {
        $writer->endElement();
        $writer->endDocument();
    }

    /**
     * @param \XMLWriter $writer
     * @param array      $namespaces
     */
    private function writeNamespaces(\XMLWriter $writer, array $namespaces)
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
    private function listNamespaces(array $namespaces): array
    {
        $result = $this->basicNamespaces();
        foreach ($namespaces as $namespace) {
            $result = $this->addNamespace($namespace, $result);
        }

        return $result;
    }

    /**
     * @return array
     */
    private function basicNamespaces(): array
    {
        return $this->addNamespace($this->namespaces->getDefault());
    }

    /**
     * @param Entities\SitemapNamespace $namespace
     * @param array                     $namespaces
     *
     * @return array
     */
    private function addNamespace(Entities\SitemapNamespace $namespace, array $namespaces = []): array
    {
        $namespaces[$namespace->getID()] = $namespace;

        return $namespaces;
    }
}