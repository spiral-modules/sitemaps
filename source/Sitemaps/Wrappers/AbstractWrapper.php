<?php

namespace Spiral\Sitemaps\Wrappers;

use Spiral\Sitemaps\SitemapsConfig;
use Spiral\Sitemaps\WrapperInterface;

abstract class AbstractWrapper implements WrapperInterface
{
    const WRAPPER = null;

    /** @var SitemapsConfig */
    protected $config;

    /**
     * @param SitemapsConfig $config
     */
    public function __construct(SitemapsConfig $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function header(): string
    {
        return "{$this->config->xmlHeader()}<{$this->tag()} {$this->namespaces()}>";
    }

    /**
     * {@inheritdoc}
     */
    public function footer(): string
    {
        return "</{$this->tag()}>";
    }

    /**
     * Open/close tag name.
     *
     * @return string
     */
    protected function tag(): string
    {
        return $this->config->wrapTag(static::WRAPPER);
    }

    /**
     * @return string
     */
    abstract protected function namespaces(): string;
}