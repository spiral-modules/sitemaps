<?php

namespace Spiral\Sitemaps\Configs;

use Spiral\Sitemaps\Transports;

class TransportConfig
{
    protected $config = [
        'modes' => [
            Transports\FileTransport::class => 'wb',
            Transports\GZIPTransport::class => 'wb9',
        ]
    ];

    /**
     * @param string $transport
     *
     * @return string
     */
    public function getMode(string $transport): string
    {
        return $this->config['modes'][$transport];
    }
}