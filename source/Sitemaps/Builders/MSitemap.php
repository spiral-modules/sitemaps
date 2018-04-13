<?php

namespace Spiral\Sitemaps\Builders;

use Spiral\Sitemaps\Elements;

class MSitemap extends AbstractBuilder
{
    /**
     * @param Elements\MultiLangURL $url
     *
     * @return bool
     */
    public function addURL(Elements\MultiLangURL $url)
    {
        return $this->addElement($url);
    }
}