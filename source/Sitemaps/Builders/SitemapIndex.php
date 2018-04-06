<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Configs\BuilderConfig;
use Spiral\Sitemaps\Configurator;
use Spiral\Sitemaps\DeclarationIndex;
use Spiral\Sitemaps\Entities;
use Spiral\Sitemaps\Patterns\SitemapPattern;
use Spiral\Sitemaps\Reservation;
use Spiral\Sitemaps\Validators\SitemapValidator;

class SitemapIndex extends AbstractBuilder
{
    public function __construct(
        SitemapPattern $pattern,
        DeclarationIndex $declaration,
        SitemapValidator $validator,
        Reservation $reservation,
        Configurator $configurator,
        BuilderConfig $config
    ) {
        parent::__construct($pattern, $declaration, $validator, $reservation, $configurator, $config);
    }

    /**
     * @param Entities\Sitemap $sitemap
     *
     * @return bool
     */
    public function addSitemap(Entities\Sitemap $sitemap): bool
    {
        return $this->addElement($sitemap);
    }
}