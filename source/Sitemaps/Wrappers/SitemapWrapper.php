<?php

namespace Spiral\Sitemaps\Wrappers;

class SitemapWrapper extends AbstractWrapper
{
    const WRAPPER = 'sitemap';

    /** @var array */
    protected $namespaces = [];

    /**
     * Add namespaces (in case if any other except default are required).
     *
     * @param array $namespaces
     *
     * @return SitemapWrapper
     */
    public function withNamespaces(array $namespaces): self
    {
        $output = [];

        foreach ($namespaces as $namespace) {
            $output[] = $this->config->getNamespace($namespace);
        }

        $clone = clone $this;
        $clone->namespaces = $output;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    protected function namespaces(): string
    {
        $namespaces = $this->namespaces;
        $namespaces[] = $this->config->getNamespace('default');

        return join(' ', array_unique($namespaces));
    }
}