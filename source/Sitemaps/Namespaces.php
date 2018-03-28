<?php

namespace Spiral\Sitemaps;

use Spiral\Sitemaps\Configs\NamespacesConfig;

class Namespaces
{
    const DEFAULT = 'default';
    const IMAGE   = 'image';
    const LANG    = 'lang';
    const VIDEO   = 'video';

    /** @var NamespacesConfig */
    protected $config;

    public function __construct(NamespacesConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Fetch namespaces by theirs short aliases (in case if any other except default are required).
     *
     * @param array $namespaces
     *
     * @return array
     */
    public function get(array $namespaces): array
    {
        $output = [];
        $namespaces['default'] = 'default';

        foreach ($namespaces as $namespace) {
            $output[$namespace] = $this->config->getNamespace($namespace);
        }

        return array_unique($output);
    }
}