<?php

namespace Spiral\Sitemaps\Configs;

use Spiral\Sitemaps\Builders;

class DeclarationConfig
{
    //const CONFIG = 'modules/sitemaps/declaration';

    protected $config = [
        'indent'        => true,
        'indentString'  => '    ',
        'version'       => '1.0',
        'encoding'      => 'UTF-8',
        'root-elements' => [
            Builders\Sitemap::class      => 'urlset',
            Builders\SitemapIndex::class => 'sitemapindex',
        ]
    ];

    /**
     * @return bool
     */
    public function indent(): bool
    {
        return $this->config['indent'];
    }

    /**
     * @return string
     */
    public function indentString(): string
    {
        return $this->config['indentString'];
    }

    /**
     * @return string
     */
    public function version(): string
    {
        return $this->config['version'];
    }

    /**
     * @return string
     */
    public function encoding(): string
    {
        return $this->config['encoding'];
    }

    /**
     * @param string $builder
     *
     * @return string
     */
    public function rootElement(string $builder): string
    {
        return $this->config['root-elements'][$builder];
    }
}