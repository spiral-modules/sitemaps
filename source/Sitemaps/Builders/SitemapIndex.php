<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Configs\BuilderConfig;
use Spiral\Sitemaps\Configurator;
use Spiral\Sitemaps\Declaration;
use Spiral\Sitemaps\Elements;
use Spiral\Sitemaps\ElementInterface;
use Spiral\Sitemaps\Patterns\SitemapPattern;
use Spiral\Sitemaps\Reservation;
use Spiral\Sitemaps\Validators\SitemapValidator;

/**
 * @link https://support.google.com/webmasters/answer/75712
 */
class SitemapIndex extends AbstractBuilder
{
    /** @var SitemapPattern */
    private $pattern;

    /**
     * {@inheritdoc}
     * @param SitemapPattern $pattern
     */
    public function __construct(
        Declaration $declaration,
        SitemapValidator $validator,
        Reservation $reservation,
        Configurator $configurator,
        BuilderConfig $config,
        SitemapPattern $pattern
    ) {
        parent::__construct($declaration, $validator, $reservation, $configurator, $config);

        $this->pattern = $pattern;
    }

    /**
     * @param Elements\Sitemap $sitemap
     *
     * @return bool
     */
    public function addSitemap(Elements\Sitemap $sitemap): bool
    {
        return $this->addElement($sitemap);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(\XMLWriter $writer, ElementInterface $element)
    {
        $this->pattern->write($writer, $element);
    }
}