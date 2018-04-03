<?php

namespace Spiral\Sitemaps;

use Spiral\Sitemaps\Configs;
use Spiral\Sitemaps\Entities;
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
     * @return Entities\SitemapNamespace
     * @throws Exceptions\UnknownNamespaceAliasException
     */
    public function getByAlias(string $alias): Entities\SitemapNamespace
    {
        if (!$this->config->hasAlias(strtolower($alias))) {
            throw new Exceptions\UnknownNamespaceAliasException("Unknown namespace alias [$alias].");
        }

        $namespace = $this->config->getNamespace($alias);

        return new Entities\SitemapNamespace($namespace['name'], $namespace['uri']);
    }

    /**
     * @param string|null $name
     * @param string      $uri
     *
     * @return Entities\SitemapNamespace
     * @throws Exceptions\InvalidNamespaceException
     */
    public function get(string $name = null, string $uri): Entities\SitemapNamespace
    {
        $this->validator->validate($name, $uri);

        return new Entities\SitemapNamespace($name, $uri);
    }

    public function getDefault(): Entities\SitemapNamespace
    {
        return $this->getByAlias(self::DEFAULT);
    }
}