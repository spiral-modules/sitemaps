<?php

namespace Spiral\Sitemaps;

use Spiral\Sitemaps\Configs;
use Spiral\Sitemaps\Elements;
use Spiral\Sitemaps\Exceptions;
use Spiral\Sitemaps\Validators;

class Namespaces
{
    const DEFAULT = 'default';
    const IMAGE   = 'image';
    const LANG    = 'lang';
    const VIDEO   = 'video';

    /** @var Configs\NamespacesConfig */
    private $config;

    /** @var Validators\NamespaceValidator */
    private $validator;

    /**
     * @param Configs\NamespacesConfig      $config
     * @param Validators\NamespaceValidator $validator
     */
    public function __construct(Configs\NamespacesConfig $config, Validators\NamespaceValidator $validator)
    {
        $this->config = $config;
        $this->validator = $validator;
    }

    /**
     * @param string $alias
     *
     * @return Elements\SitemapNamespace
     * @throws Exceptions\UnknownNamespaceAliasException
     */
    public function getByAlias(string $alias): Elements\SitemapNamespace
    {
        if (!$this->config->hasAlias(strtolower($alias))) {
            throw new Exceptions\UnknownNamespaceAliasException("Unknown namespace alias [$alias].");
        }

        $namespace = $this->config->getNamespace($alias);

        return new Elements\SitemapNamespace($namespace['name'], $namespace['uri']);
    }

    /**
     * @param string|null $name
     * @param string      $uri
     *
     * @return Elements\SitemapNamespace
     * @throws Exceptions\InvalidNamespaceException
     */
    public function get(string $name = null, string $uri): Elements\SitemapNamespace
    {
        $this->validator->validate($name, $uri);

        return new Elements\SitemapNamespace($name, $uri);
    }

    public function getDefault(): Elements\SitemapNamespace
    {
        return $this->getByAlias(self::DEFAULT);
    }
}