<?php

namespace Spiral\Tests\Sitemaps\Items;

use Spiral\Database\Entities\Database;
use Spiral\Sitemaps\Items\AlterLangItem;
use Spiral\Sitemaps\Items\ImageItem;
use Spiral\Sitemaps\Items\PageItem;
use Spiral\Tests\BaseTest;

class PageItemTest extends BaseTest
{
    /**
     * @expectedException \Spiral\Sitemaps\Exceptions\InvalidFrequencyException
     */
    public function testInvalidFrequency()
    {
        $item = new PageItem('location.com', null, 'some frequency');
    }

    /**
     * @expectedException \Spiral\Sitemaps\Exceptions\InvalidPriorityException
     */
    public function testInvalidPriority()
    {
        $item = new PageItem('location.com', null, 'daily', 2);
    }

    public function testRender()
    {
        $time = new \DateTime();
        $item = new PageItem(
            'location.com',
            $time,
            'daily',
            0.6
        );

        $item->addImage(new ImageItem('location.image'));
        $item->addImage(new ImageItem('location.image2'));

        $item->addAlterLang(new AlterLangItem('de', 'location.de'));
        $item->addAlterLang(new AlterLangItem('ru', 'location.ru'));

        $render = $item->render();

        $this->assertContains('<loc>location.com</loc>', $render);
        $this->assertContains('<lastmod>', $render);
        $this->assertContains('<changefreq>daily</changefreq>', $render);
        $this->assertContains('<priority>0.6</priority>', $render);

        $this->assertContains('<image:image>', $render);
        $this->assertContains('</image:image>', $render);
        $this->assertContains('<image:loc>location.image</image:loc>', $render);
        $this->assertContains('<image:loc>location.image2</image:loc>', $render);

        $this->assertContains('hreflang="de"', $render);
        $this->assertContains('href="location.de"', $render);
        $this->assertContains('hreflang="ru"', $render);
        $this->assertContains('href="location.ru"', $render);
        $this->assertContains('rel="alternate"', $render);
        $this->assertContains('<xhtml:link rel="alternate"', $render);

        $this->assertContains('<url>', $render);
        $this->assertContains('</url>', $render);
    }

    /**
     * Not containing empty tags.
     */
    public function testEmptyRender()
    {
        $item = new PageItem('location.com');

        $render = $item->render();

        $this->assertContains('<loc>location.com</loc>', $render);
        $this->assertNotContains('<lastmod>', $render);
        $this->assertNotContains('<changefreq>daily</changefreq>', $render);
        $this->assertNotContains('<priority>0.6</priority>', $render);

        $this->assertNotContains('<image:image>', $render);
        $this->assertNotContains('</image:image>', $render);
        $this->assertNotContains('<image:loc>location.image</image:loc>', $render);
        $this->assertNotContains('<image:loc>location.image2</image:loc>', $render);

        $this->assertNotContains('hreflang="de"', $render);
        $this->assertNotContains('href="location.de"', $render);
        $this->assertNotContains('hreflang="ru"', $render);
        $this->assertNotContains('href="location.ru"', $render);
        $this->assertNotContains('rel="alternate"', $render);
        $this->assertNotContains('<xhtml:link rel="alternate"', $render);

        $this->assertContains('<url>', $render);
        $this->assertContains('</url>', $render);
    }
}