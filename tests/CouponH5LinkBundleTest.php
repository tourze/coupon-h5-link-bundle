<?php

namespace Tourze\CouponH5LinkBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\CouponH5LinkBundle\CouponH5LinkBundle;

class CouponH5LinkBundleTest extends TestCase
{
    private CouponH5LinkBundle $bundle;

    public function testIsInstanceOfBundle(): void
    {
        $this->assertInstanceOf(Bundle::class, $this->bundle);
    }

    public function testGetName(): void
    {
        $this->assertSame('CouponH5LinkBundle', $this->bundle->getName());
    }

    public function testGetNamespace(): void
    {
        $this->assertSame('Tourze\CouponH5LinkBundle', $this->bundle->getNamespace());
    }

    public function testGetPath(): void
    {
        $expectedPath = dirname(__DIR__) . '/src';
        $this->assertSame($expectedPath, $this->bundle->getPath());
    }

    public function testGetContainerExtension(): void
    {
        $extension = $this->bundle->getContainerExtension();
        $this->assertInstanceOf(\Tourze\CouponH5LinkBundle\DependencyInjection\CouponH5LinkExtension::class, $extension);
    }

    protected function setUp(): void
    {
        $this->bundle = new CouponH5LinkBundle();
    }
}