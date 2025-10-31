<?php

declare(strict_types=1);

namespace Tourze\CouponH5LinkBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\Arrayable\ApiArrayInterface;
use Tourze\CouponCoreBundle\Entity\Coupon;
use Tourze\CouponH5LinkBundle\Entity\H5Link;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(H5Link::class)]
final class H5LinkTest extends AbstractEntityTestCase
{
    public function testInitialState(): void
    {
        $h5Link = $this->createEntity();
        $this->assertNull($h5Link->getId());
        $this->assertNull($h5Link->getUrl());
        $this->assertNull($h5Link->getCoupon());
        $this->assertNull($h5Link->getCreatedFromIp());
        $this->assertNull($h5Link->getUpdatedFromIp());
    }

    public function testSetAndGetUrl(): void
    {
        $url = 'https://example.com/coupon';
        $h5Link = $this->createEntity();
        $h5Link->setUrl($url);

        $this->assertSame($url, $h5Link->getUrl());
    }

    public function testSetAndGetCoupon(): void
    {
        // 使用真实的 Coupon 实体实例，避免 Mock 以满足静态分析要求
        // 这里只是测试 setter/getter 行为，不需要 Coupon 的具体实现逻辑
        $coupon = new Coupon();
        $h5Link = $this->createEntity();
        $h5Link->setCoupon($coupon);

        $this->assertSame($coupon, $h5Link->getCoupon());
    }

    public function testSetAndGetCreatedFromIp(): void
    {
        $ip = '192.168.1.1';
        $h5Link = $this->createEntity();
        $h5Link->setCreatedFromIp($ip);

        $this->assertSame($ip, $h5Link->getCreatedFromIp());
    }

    public function testSetAndGetUpdatedFromIp(): void
    {
        $ip = '192.168.1.100';
        $h5Link = $this->createEntity();
        $h5Link->setUpdatedFromIp($ip);

        $this->assertSame($ip, $h5Link->getUpdatedFromIp());
    }

    public function testSetCreatedFromIpWithNull(): void
    {
        $h5Link = $this->createEntity();
        $h5Link->setCreatedFromIp('192.168.1.1');
        $h5Link->setCreatedFromIp(null);

        $this->assertNull($h5Link->getCreatedFromIp());
    }

    public function testSetUpdatedFromIpWithNull(): void
    {
        $h5Link = $this->createEntity();
        $h5Link->setUpdatedFromIp('192.168.1.1');
        $h5Link->setUpdatedFromIp(null);

        $this->assertNull($h5Link->getUpdatedFromIp());
    }

    public function testRetrieveApiArrayWithoutTimestamps(): void
    {
        $url = 'https://example.com/coupon';
        $h5Link = $this->createEntity();
        $h5Link->setUrl($url);

        $apiArray = $h5Link->retrieveApiArray();

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
        $h5Link = $this->createEntity();
        $result = (string) $h5Link;
        $this->assertSame('H5Link#new', $result);
    }

    public function testToStringWithId(): void
    {
        $h5Link = $this->createEntity();
        $reflectionClass = new \ReflectionClass($h5Link);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($h5Link, '123456789');

        $result = (string) $h5Link;
        $this->assertSame('H5Link#123456789', $result);
    }

    public function testStringableInterface(): void
    {
        $h5Link = $this->createEntity();
        $this->assertInstanceOf(\Stringable::class, $h5Link);
    }

    public function testApiArrayInterface(): void
    {
        $h5Link = $this->createEntity();
        $this->assertInstanceOf(ApiArrayInterface::class, $h5Link);
    }

    public function testFluentInterface(): void
    {
        $url = 'https://example.com/coupon';
        $ip = '192.168.1.1';
        // 使用真实的 Coupon 实体实例，避免 Mock 以满足静态分析要求
        // 这里只是测试 setter/getter 行为，不需要 Coupon 的具体实现逻辑
        $coupon = new Coupon();
        $h5Link = $this->createEntity();

        $h5Link->setUrl($url);
        $h5Link->setCreatedFromIp($ip);
        $h5Link->setUpdatedFromIp($ip);
        $h5Link->setCoupon($coupon);
        $this->assertSame($url, $h5Link->getUrl());
        $this->assertSame($ip, $h5Link->getCreatedFromIp());
        $this->assertSame($ip, $h5Link->getUpdatedFromIp());
        $this->assertSame($coupon, $h5Link->getCoupon());
    }

    protected function createEntity(): H5Link
    {
        return new H5Link();
    }

    /**
     * 提供属性及其样本值的 Data Provider.
     *
     * @return iterable<string, array{string, string}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'url' => ['url', 'https://example.com/coupon'];
        yield 'createdFromIp' => ['createdFromIp', '192.168.1.1'];
        yield 'updatedFromIp' => ['updatedFromIp', '192.168.1.100'];
    }
}
