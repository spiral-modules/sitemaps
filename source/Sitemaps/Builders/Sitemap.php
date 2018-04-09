<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Configs\BuilderConfig;
use Spiral\Sitemaps\Configurator;
use Spiral\Sitemaps\Declaration;
use Spiral\Sitemaps\Elements\URL;
use Spiral\Sitemaps\ElementInterface;
use Spiral\Sitemaps\Reservation;
use Spiral\Sitemaps\Validators\SitemapValidator;
use Spiral\Sitemaps\Patterns\URLPattern;

class Sitemap extends AbstractBuilder
{
    /** @var \Spiral\Sitemaps\Patterns\URLPattern */
    private $pattern;

    public function __construct(
        Declaration $declaration,
        SitemapValidator $validator,
        Reservation $reservation,
        Configurator $configurator,
        BuilderConfig $config,
        URLPattern $pattern
    ) {
        parent::__construct($declaration, $validator, $reservation, $configurator, $config);

        $this->pattern = $pattern;
    }

    /**
     * @param URL $url
     *
     * @return bool
     */
    public function addURL(URL $url)
    {
        return $this->addElement($url);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(\XMLWriter $writer, ElementInterface $element)
    {
        $this->pattern->write($writer, $element);
    }
}