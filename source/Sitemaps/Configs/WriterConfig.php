<?php

namespace Spiral\Sitemaps\Configs;

class WriterConfig
{
    protected $config = [
        'indent'       => true,
        'indentString' => '    ',
    ];

    public function indent(): bool
    {
        return $this->config['indent'];
    }

    public function indentString(): string
    {
        return $this->config['indentString'];
    }
}