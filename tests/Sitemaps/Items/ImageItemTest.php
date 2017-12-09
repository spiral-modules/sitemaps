<?php

namespace Spiral\Tests\Sitemaps\Items;

use Spiral\Sitemaps\Items\Image;
use Spiral\Tests\BaseTest;

class ImageItemTest extends BaseTest
{
    public function testRender()
    {
        $item = new Image(
            'location.com',
            'some caption',
            'some geo location',
            'some title',
            'license.com'
        );

        $render = $item->render();

        $this->assertContains('<image:loc>location.com</image:loc>', $render);
        $this->assertContains('<image:caption><![CDATA[some caption]]></image:caption>', $render);
        $this->assertContains('<image:geo_location>some geo location</image:geo_location>', $render);
        $this->assertContains('<image:title><![CDATA[some title]]></image:title>', $render);
        $this->assertContains('<image:license>license.com</image:license>', $render);

        $this->assertContains('<image:image>', $render);
        $this->assertContains('</image:image>', $render);
    }

    /**
     * Not containing empty tags.
     */
    public function testEmptyRender()
    {
        $item = new Image('location.com');

        $render = $item->render();

        $this->assertContains('<image:loc>location.com</image:loc>', $render);
        $this->assertNotContains('<image:caption><![CDATA[some caption]]></image:caption>', $render);
        $this->assertNotContains('<image:geo_location>some geo location</image:geo_location>', $render);
        $this->assertNotContains('<image:title><![CDATA[some title]]></image:title>', $render);
        $this->assertNotContains('<image:license>license.com</image:license>', $render);

        $this->assertContains('<image:image>', $render);
        $this->assertContains('</image:image>', $render);
    }
}