<?php

namespace Spiral;

use Spiral\Core\DirectoriesInterface;
use Spiral\Modules\ModuleInterface;
use Spiral\Modules\PublisherInterface;
use Spiral\Modules\RegistratorInterface;
use Spiral\Sitemaps\SitemapsConfig;

/**
 * Class SitemapsModule
 *
 * Some wiki-links below
 *
 * @link    https://www.sitemaps.org/ru/protocol.html general information
 * @link    https://support.google.com/webmasters/topic/6080646 general information
 * @link    https://support.google.com/webmasters/answer/178636 images sitemap
 * @link    https://support.google.com/webmasters/answer/2620865 alter langs sitemsp
 * @link    https://developers.google.com/webmasters/videosearch/sitemaps video sitemsp
 *
 * @package Spiral
 */
class SitemapsModule implements ModuleInterface
{
    /**
     * @inheritDoc
     */
    public function register(RegistratorInterface $registrator)
    {
    }

    /**
     * @inheritDoc
     */
    public function publish(PublisherInterface $publisher, DirectoriesInterface $directories)
    {
        //Publish config
        $publisher->publish(
            __DIR__ . '/config/config.php',
            $directories->directory('config') . SitemapsConfig::CONFIG . '.php',
            PublisherInterface::FOLLOW
        );
    }
}