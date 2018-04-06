<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Configs\BuilderConfig;
use Spiral\Sitemaps\Configurator;
use Spiral\Sitemaps\Declaration;
use Spiral\Sitemaps\Entities;
use Spiral\Sitemaps\Exceptions\EnormousElementException;
use Spiral\Sitemaps\Reservation;
use Spiral\Sitemaps\Utils;
use Spiral\Sitemaps\Validators\SitemapValidator;
use Spiral\Sitemaps\Patterns\URLPattern;

class Sitemap extends AbstractBuilder
{
    public function __construct(
        URLPattern $pattern,
        Declaration $declaration,
        SitemapValidator $validator,
        Reservation $reservation,
        Configurator $configurator,
        BuilderConfig $config
    ) {
        parent::__construct($pattern, $declaration, $validator, $reservation, $configurator, $config);
    }

    /**
     * @param Entities\URL $url
     *
     * @return bool
     */
    public function addURL(Entities\URL $url)
    {
        return $this->addElement($url);
    }
}