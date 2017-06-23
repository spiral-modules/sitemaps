<?php

namespace Spiral\Tests\Sitemaps;

use Psr\Log\LogLevel;
use Spiral\Debug\Traits\LoggerTrait;
use Spiral\LogViewer\Entities\LogFile;
use Spiral\LogViewer\Services\LogService;
use Spiral\Sitemaps\Sitemaps;
use Spiral\Tests\HttpTest;

class SitemapsTest extends HttpTest
{
    use LoggerTrait;

    /**
     * @return \Spiral\Sitemaps\Sitemaps
     */
    protected function sitemaps(): Sitemaps
    {
        return $this->container->get(Sitemaps::class);
    }

    public function test()
    {
        $sitemaps = $this->sitemaps();
        $this->assertTrue(true);
    }
}