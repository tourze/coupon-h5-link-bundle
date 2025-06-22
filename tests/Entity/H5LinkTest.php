<?php

namespace Tourze\CouponH5LinkBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\CouponCoreBundle\Entity\Coupon;
use Tourze\CouponH5LinkBundle\Entity\H5Link;

class H5LinkTest extends TestCase
{
    private H5Link $h5Link;

    public function testInitialState(): void
    {
        $this->assertNull($this->h5Link->getId());
        $this->assertNull($this->h5Link->getUrl());
        $this->assertNull($this->h5Link->getCoupon());
        $this->assertNull($this->h5Link->getCreatedFromIp());
        $this->assertNull($this->h5Link->getUpdatedFromIp());
    }

    public function testSetAndGetUrl(): void
    {
        $url = 'https://example.com/coupon';
        $result = $this->h5Link->setUrl($url);

        $this->assertSame($this->h5Link, $result);
        $this->assertSame($url, $this->h5Link->getUrl());
    }

    public function testSetAndGetCoupon(): void
    {
        $coupon = $this->createMock(Coupon::class);
        $result = $this->h5Link->setCoupon($coupon);

        $this->assertSame($this->h5Link, $result);
        $this->assertSame($coupon, $this->h5Link->getCoupon());
    }

    public function testSetAndGetCreatedFromIp(): void
    {
        $ip = '192.168.1.1';
        $result = $this->h5Link->setCreatedFromIp($ip);

        $this->assertSame($this->h5Link, $result);
        $this->assertSame($ip, $this->h5Link->getCreatedFromIp());
    }

    public function testSetAndGetUpdatedFromIp(): void
    {
        $ip = '192.168.1.100';
        $result = $this->h5Link->setUpdatedFromIp($ip);

        $this->assertSame($this->h5Link, $result);
        $this->assertSame($ip, $this->h5Link->getUpdatedFromIp());
    }

    public function testSetCreatedFromIpWithNull(): void
    {
        $this->h5Link->setCreatedFromIp('192.168.1.1');
        $result = $this->h5Link->setCreatedFromIp(null);

        $this->assertSame($this->h5Link, $result);
        $this->assertNull($this->h5Link->getCreatedFromIp());
    }

    public function testSetUpdatedFromIpWithNull(): void
    {
        $this->h5Link->setUpdatedFromIp('192.168.1.1');
        $result = $this->h5Link->setUpdatedFromIp(null);

        $this->assertSame($this->h5Link, $result);
        $this->assertNull($this->h5Link->getUpdatedFromIp());
    }

    public function testRetrieveApiArrayWithoutTimestamps(): void
    {
        $url = 'https://example.com/coupon';
        $this->h5Link->setUrl($url);

        $apiArray = $this->h5Link->retrieveApiArray();

        $this->assertIsArray($apiArray);
        $this->assertArrayHasKey('id', $apiArray);
        $this->assertArrayHasKey('createTime', $apiArray);
        $this->assertArrayHasKey('updateTime', $apiArray);
        $this->assertArrayHasKey('url', $apiArray);
        $this->assertSame($url, $apiArray['url']);
        $this->assertNull($apiArray['createTime']);
        $this->assertNull($apiArray['updateTime']);
    }

    public function testToStringWithoutId(): void
    {
        $result = (string) $this->h5Link;
        $this->assertSame('H5Link#new', $result);
    }

    public function testToStringWithId(): void
    {
        $reflectionClass = new \ReflectionClass($this->h5Link);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->h5Link, '123456789');

        $result = (string) $this->h5Link;
        $this->assertSame('H5Link#123456789', $result);
    }

    public function testStringableInterface(): void
    {
        $this->assertInstanceOf(\Stringable::class, $this->h5Link);
    }

    public function testApiArrayInterface(): void
    {
        $this->assertInstanceOf(\Tourze\Arrayable\ApiArrayInterface::class, $this->h5Link);
    }

    public function testFluentInterface(): void
    {
        $url = 'https://example.com/coupon';
        $ip = '192.168.1.1';
        $coupon = $this->createMock(Coupon::class);

        $result = $this->h5Link
            ->setUrl($url)
            ->setCreatedFromIp($ip)
            ->setUpdatedFromIp($ip)
            ->setCoupon($coupon);

        $this->assertSame($this->h5Link, $result);
        $this->assertSame($url, $this->h5Link->getUrl());
        $this->assertSame($ip, $this->h5Link->getCreatedFromIp());
        $this->assertSame($ip, $this->h5Link->getUpdatedFromIp());
        $this->assertSame($coupon, $this->h5Link->getCoupon());
    }

    protected function setUp(): void
    {
        $this->h5Link = new H5Link();
    }
}