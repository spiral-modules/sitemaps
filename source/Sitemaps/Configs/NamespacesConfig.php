<?php

namespace Spiral\Sitemaps\Configs;

use Spiral\Core\InjectableConfig;

class NamespacesConfig extends InjectableConfig
{
    const CONFIG = 'modules/sitemaps/namespaces';

    protected $config = [
        'default' => [
            'namespace' => 'xmlns',
            'uri'       => 'http://www.sitemaps.org/schemas/sitemap/0.9'
        ],
        'image'   => [
            'namespace' => 'xmlns:image',
            'uri'       => 'http://www.google.com/schemas/sitemap-image/1.1'
        ],
        'lang'    => [
            'namespace' => 'xmlns:xhtml',
            'uri'       => 'http://www.w3.org/1999/xhtml'
        ],
        'video'   => [
            'namespace' => 'xmlns:video',
            'uri'       => 'http://www.google.com/schemas/sitemap-video/1.1'
        ]
    ];
}