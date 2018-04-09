<?php

namespace Spiral\Sitemaps\Validators;

use Spiral\Sitemaps\Configs\BuilderConfig;
use Spiral\Sitemaps\Writer\State;

class SitemapValidator
{
    private $config;

    public function __construct(BuilderConfig $config)
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
        return $size > $this->config->filesize() - $state->getReservedSize();
    }

    /**
     * @link https://www.sitemaps.org/protocol.html#index
     *
     * @param State $state
     * @param int   $size
     *
     * @return bool
     */
    public function validate(State $state, int $size): bool
    {
        return $this->newElementAllowed($state) && $this->filesizeAllowed($state, $size);
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
     * @param int   $size
     *
     * @return bool
     */
    private function filesizeAllowed(State $state, int $size): bool
    {
        return $size <= ($this->config->filesize() - $state->getReservedSize() - $state->getCurrentSize());
    }
}