<?php

namespace Spiral\Sitemaps\Configs;

//todo sitemapindex root element
class DeclarationConfig
{
    //const CONFIG = 'modules/sitemaps/declaration';

    protected $config = [
        'indent'       => true,
        'indentString' => '    ',
        'version'      => '1.0',
        'encoding'     => 'UTF-8',
        'root-element' => 'urlset'
    ];

    public function indent(): bool
    {
        return $this->config['indent'];
    }

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

    public function rootElement(): string
    {
        return $this->config['root-element'];
    }
}