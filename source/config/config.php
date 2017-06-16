<?php

return [
    'xmlHeader'       => '<?xml version="1.0" encoding="UTF-8"?>',
    'sitemaps'        => [
        'pages'    => [
            'wrapTag'     => 'urlset',
            'maxFiles'    => 50000,
            //49.59mb actually - a little bit smaller than 50mb, enough to write closing tag
            'maxFileSize' => 52000000,
        ],
        'sitemaps' => [
            'wrapTag'  => 'sitemapindex',
            'maxFiles' => 500
        ],
    ],
    'directory'       => 'sitemaps/',
    'knownNamespaces' => [
        'default'   => 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"',
        'image'     => 'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"',
        'video'     => 'xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"',
        'alterLang' => 'xmlns:xhtml="http://www.w3.org/1999/xhtml"',
    ]
];