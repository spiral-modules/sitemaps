<?php

return [
    \Spiral\Sitemaps\Namespaces::DEFAULT => [
        'namespace' => 'xmlns',
        'uri'       => 'http://www.sitemaps.org/schemas/sitemap/0.9'
    ],
    \Spiral\Sitemaps\Namespaces::IMAGE   => [
        'namespace' => 'xmlns:image',
        'uri'       => 'http://www.google.com/schemas/sitemap-image/1.1'
    ],
    \Spiral\Sitemaps\Namespaces::LANG    => [
        'namespace' => 'xmlns:xhtml',
        'uri'       => 'http://www.w3.org/1999/xhtml'
    ],
    \Spiral\Sitemaps\Namespaces::VIDEO   => [
        'namespace' => 'xmlns:video',
        'uri'       => 'http://www.google.com/schemas/sitemap-video/1.1'
    ]
];