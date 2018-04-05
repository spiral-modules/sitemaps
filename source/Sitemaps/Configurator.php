<?php

namespace Spiral\Sitemaps;

use Spiral\Sitemaps\Configs\DeclarationConfig;

class Configurator
{
    private $config;

    public function __construct(DeclarationConfig $config)
    {
        $this->config = $config;
    }

    public function configure(\XMLWriter $writer)
    {
        $writer->setIndent($this->config->indent());

        if ($this->config->indent()) {
            $writer->setIndentString($this->config->indentString());
        }

        return $writer;
    }
}