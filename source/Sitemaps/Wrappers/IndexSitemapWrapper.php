<?php

namespace Spiral\Sitemaps\Wrappers;

class IndexSitemapWrapper extends AbstractWrapper
{
    const WRAPPER = 'index';

    /**
     * {@inheritdoc}
     */
    protected function namespaces(): string
    {
        return $this->config->getNamespace('default');
    }
}