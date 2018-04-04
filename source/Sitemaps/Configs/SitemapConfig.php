<?php

namespace Spiral\Sitemaps\Configs;

class SitemapConfig
{
    protected $config = [
        'max-elements' => 50000,
        'file-size'    => 10 * 1024 * 1024,
    ];

    /**
     * @return int
     */
    public function maxElements(): int
    {
        return $this->config['max-elements'];
    }

    /**
     * @return int
     */
    public function filesize(): int
    {
        return $this->config['file-size'];
    }
}