<?php

namespace Spiral\Tests\Sitemaps\Items;

use Spiral\Sitemaps\Items\AlterLangItem;
use Spiral\Tests\BaseTest;

class AlterLangItemTest extends BaseTest
{
    public function testRender()
    {
        $item = new AlterLangItem('en', 'location.en');

        $render = $item->render();

        $this->assertContains('hreflang="en"', $render);
        $this->assertContains('href="location.en"', $render);
        $this->assertContains('rel="alternate"', $render);
        $this->assertContains('<xhtml:link rel="alternate"', $render);
    }
}