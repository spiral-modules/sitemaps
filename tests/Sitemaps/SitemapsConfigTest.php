<?php

namespace Spiral\Tests\Sitemaps;

use Spiral\Sitemaps\SitemapsConfig;
use Spiral\Tests\BaseTest;

class SitemapsConfigTest extends BaseTest
{
    /**
     * @return \Spiral\Sitemaps\SitemapsConfig
     */
    protected function getConfig(): SitemapsConfig
    {
        return $this->container->get(SitemapsConfig::class);
    }

    /**
     * Aliases.
     */
    public function testAliases()
    {
        $config = $this->getConfig();
        $this->assertEquals('foo', $config->getNamespace('foo'));
        $this->assertNotEquals('foo', $config->getNamespace('image'));
    }
}