<?php

namespace Spiral\Sitemaps;

class Namespaces
{
    /**
     * @var SitemapsConfig
     */
    protected $config;

    /**
     * Namespaces constructor.
     *
     * @param SitemapsConfig $config
     */
    public function __construct(SitemapsConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Fetch namespaces by theirs short aliases (in case if any other except default are required).
     *
     * @param array $namespaces
     * @return array
     */
    public function get(array $namespaces): array
    {
        $output = [];
        $namespaces[] = 'default';

        foreach ($namespaces as $namespace) {
            $output[] = $this->config->getNamespace($namespace);
        }

        return $output;
    }
}