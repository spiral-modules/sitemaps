<?php

namespace Spiral\Sitemaps\Validators;

use Spiral\Sitemaps\Configs\SitemapConfig;
use Spiral\Sitemaps\Writer\State;

class SitemapValidator
{
    private $config;

    public function __construct(SitemapConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param State $state
     * @param int   $size
     *
     * @return bool
     */
    public function isEnormousElement(State $state, int $size)
    {
        return $size > $this->config->filesize() - $state->getReservedFilesize();
    }

    /**
     * @param State $state
     * @param int   $filesize
     *
     * @return bool
     */
    public function validate(State $state, int $filesize): bool
    {
        return $this->newElementAllowed($state) && $this->filesizeAllowed($state, $filesize);
    }

    /**
     * @param State $state
     *
     * @return bool
     */
    private function newElementAllowed(State $state): bool
    {
        return $state->getCurrentElementsCount() < $this->config->maxElements();
    }

    /**
     * @param State $state
     * @param int   $filesize
     *
     * @return bool
     */
    private function filesizeAllowed(State $state, int $filesize): bool
    {
        return $filesize < ($this->config->filesize() - $state->getReservedFilesize() - $state->getCurrentFilesize());
    }
}