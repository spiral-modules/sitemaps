<?php

return [
    'sitemaps'         => [
        'maxFiles'     => 50000,
        //49.59mb actually - a little bit smaller than 50mb, enough to write closing tag
        'maxSize'      => 52000000,
        'compression'  => true,
        'subDirectory' => 'sitemaps/',
    ],
    'namespaceAliases' => [
        'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'           => ['default'],
        'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' => ['image', 'images'],
        'xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"' => ['video', 'videos'],
        'xmlns:xhtml="http://www.w3.org/1999/xhtml"'                    => [
            'alterLang',
            'alterLangs',
            'lang',
            'langs'
        ]
    ]
];