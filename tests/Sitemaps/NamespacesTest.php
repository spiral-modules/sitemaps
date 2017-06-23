<?php

namespace Spiral\Tests\Sitemaps;

use Spiral\Sitemaps\Namespaces;
use Spiral\Tests\BaseTest;

class NamespacesTest extends BaseTest
{
    public function testGetNamespaces()
    {
        /** @var Namespaces $namespaces */
        $namespaces = $this->container->get(Namespaces::class);

        //default namespace is added
        $this->assertCount(1, $namespaces->get([]));
        $this->assertCount(2, $namespaces->get(['foo']));

        //unique values
        $this->assertCount(2, $namespaces->get(['foo', 'foo']));
        $this->assertCount(3, $namespaces->get(['foo', 'image']));


        $this->assertArrayHasKey('foo', $namespaces->get(['foo']));
        $this->assertArrayHasKey('image', $namespaces->get(['foo', 'image']));

        $this->assertEquals('foo',$namespaces->get(['foo'])['foo']);
        $this->assertNotEquals('image',$namespaces->get(['image'])['image']);
    }
}