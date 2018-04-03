<?php

namespace Spiral\Sitemaps\Configs;

use Spiral\Sitemaps\Namespaces;

class NamespacesConfig
{
    //const CONFIG = 'modules/sitemaps/namespaces';

    protected $config = [
        Namespaces::DEFAULT => [
            'name' => 'xmlns',
            'uri'  => 'http://www.sitemaps.org/schemas/sitemap/0.9'
        ],
        Namespaces::IMAGE   => [
            'name' => 'xmlns:image',
            'uri'  => 'http://www.google.com/schemas/sitemap-image/1.1'
        ],
        Namespaces::LANG    => [
            'name' => 'xmlns:xhtml',
            'uri'  => 'http://www.w3.org/1999/xhtml'
        ],
        Namespaces::VIDEO   => [
            'name' => 'xmlns:video',
            'uri'  => 'http://www.google.com/schemas/sitemap-video/1.1'
        ]
    ];

    /**
     * @param string $alias
     *
     * @return bool
     */
    public function hasAlias(string $alias): bool
    {
        return isset($this->config[$alias]);
    }

    /**
     * @param string $alias
     *
     * @return array
     */
    public function getNamespace(string $alias): array
    {
        return $this->config[$alias];
    }
}