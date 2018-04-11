<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Configs\BuilderConfig;
use Spiral\Sitemaps\Configurator;
use Spiral\Sitemaps\Declaration;
use Spiral\Sitemaps\Elements;
use Spiral\Sitemaps\ElementInterface;
use Spiral\Sitemaps\Patterns\MultiLangURLPattern;
use Spiral\Sitemaps\Reservation;
use Spiral\Sitemaps\Validators\SitemapValidator;

class MSitemap extends AbstractBuilder
{
    /** @var MultiLangURLPattern */
    private $pattern;

    /**
     * {@inheritdoc}
     * @param MultiLangURLPattern $pattern
     */
    public function __construct(
        Declaration $declaration,
        SitemapValidator $validator,
        Reservation $reservation,
        Configurator $configurator,
        BuilderConfig $config,
        MultiLangURLPattern $pattern
    ) {
        parent::__construct($declaration, $validator, $reservation, $configurator, $config);

        $this->pattern = $pattern;
    }

    /**
     * @param Elements\MultiLangURL $url
     *
     * @return bool
     */
    public function addURL(Elements\MultiLangURL $url)
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