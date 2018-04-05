<?php

namespace Spiral\Sitemaps\Configs;

class BuilderConfig
{
    //const CONFIG = 'modules/sitemaps/builder';

    protected $config = [
        'buffer-elements' => 2,
        'buffer-size'     => 1024 * 1024,
        'max-elements'    => 50000,
//        'max-size'        => 472,
        'max-size'    => 10 * 1024 * 1024,
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
        return $this->config['max-size'];
    }

    /**
     * @return int
     */
    public function bufferElements(): int
    {
        return $this->config['buffer-elements'];
    }

    /**
     * @return int
     */
    public function bufferSize(): int
    {
        return $this->config['buffer-size'];
    }
}