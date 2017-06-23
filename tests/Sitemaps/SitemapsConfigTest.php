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
     * Unknown sitemap type.
     *
     * @expectedException \Spiral\Sitemaps\Exceptions\UnknownSitemapException
     */
    public function testFailMaxFilesValues()
    {
        $config = $this->getConfig();
        $config->maxFiles('some-sitemap');
    }

    /**
     * Unknown sitemap type.
     *
     * @expectedException \Spiral\Sitemaps\Exceptions\UnknownSitemapException
     */
    public function testFailMaxFileSizeValues()
    {
        $config = $this->getConfig();
        $config->maxFileSize('some-sitemap');
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