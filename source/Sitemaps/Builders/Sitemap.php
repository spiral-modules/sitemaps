<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Elements;

class Sitemap extends AbstractBuilder
{
    /**
     * @param Elements\URL $url
     *
     * @return bool
     */
    public function addURL(Elements\URL $url)
    {
        return $this->addElement($url);
    }
}