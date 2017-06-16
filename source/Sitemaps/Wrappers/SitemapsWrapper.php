<?php

namespace Spiral\Sitemaps\Wrappers;

class SitemapsWrapper extends AbstractWrapper
{
    const WRAPPER = 'sitemaps';

    /**
     * {@inheritdoc}
     */
    protected function namespaces(): string
    {
        return $this->config->getNamespace('default');
    }
}