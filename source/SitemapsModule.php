<?php

namespace Spiral;

use Spiral\Core\DirectoriesInterface;
use Spiral\Modules\ModuleInterface;
use Spiral\Modules\PublisherInterface;
use Spiral\Modules\RegistratorInterface;
use Spiral\Sitemaps\SitemapsConfig;

/**
 * Class PagesModule
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