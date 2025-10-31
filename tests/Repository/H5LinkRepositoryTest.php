<?php

namespace Tourze\CouponH5LinkBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CouponCoreBundle\Entity\Coupon;
use Tourze\CouponH5LinkBundle\Entity\H5Link;
use Tourze\CouponH5LinkBundle\Repository\H5LinkRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(H5LinkRepository::class)]
#[RunTestsInSeparateProcesses]
final class H5LinkRepositoryTest extends AbstractRepositoryTestCase
{
    private H5LinkRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(H5LinkRepository::class);
    }

    private function createTestCoupon(string $name = 'Test Coupon'): Coupon
    {
        $coupon = new Coupon();
        $coupon->setName($name);
        $coupon->setValid(true);
        $coupon->setExpireDay(30);

        self::getEntityManager()->persist($coupon);
        self::getEntityManager()->flush();

        return $coupon;
    }

    public function testIsInstanceOfH5LinkRepository(): void
    {
        $this->assertInstanceOf(H5LinkRepository::class, $this->repository);
    }

    public function testSave(): void
    {
        $coupon = $this->createTestCoupon();

        $h5Link = new H5Link();
        $h5Link->setUrl('https://example.com/test');
        $h5Link->setCoupon($coupon);

        $this->repository->save($h5Link);
        $this->assertNotNull($h5Link->getId());
    }

    public function testRemove(): void
    {
        $coupon = $this->createTestCoupon();

        $h5Link = new H5Link();
        $h5Link->setUrl('https://example.com/test');
        $h5Link->setCoupon($coupon);

        $this->repository->save($h5Link);
        $id = $h5Link->getId();

        $this->repository->remove($h5Link);
        $this->assertNull($this->repository->find($id));
    }

    public function testFindOneByWithOrderByClause(): void
    {
        $result = $this->repository->findOneBy([], ['url' => 'ASC']);
        $this->assertTrue(null === $result || $result instanceof H5Link);
    }

    public function testFindOneByAssociationCouponShouldReturnMatchingEntity(): void
    {
        $coupon = $this->createTestCoupon();

        $h5Link = new H5Link();
        $h5Link->setUrl('https://example.com/test');
        $h5Link->setCoupon($coupon);

        $this->repository->save($h5Link);

        $result = $this->repository->findOneBy(['coupon' => $coupon]);
        $this->assertSame($h5Link, $result);
    }

    public function testCountByAssociationCouponShouldReturnCorrectNumber(): void
    {
        $coupon1 = $this->createTestCoupon('Test Coupon 1');
        $coupon2 = $this->createTestCoupon('Test Coupon 2');

        $h5Link1 = new H5Link();
        $h5Link1->setUrl('https://example1.com');
        $h5Link1->setCoupon($coupon1);

        $h5Link2 = new H5Link();
        $h5Link2->setUrl('https://example2.com');
        $h5Link2->setCoupon($coupon2);

        $this->repository->save($h5Link1);
        $this->repository->save($h5Link2);

        $count = $this->repository->count(['coupon' => $coupon1]);
        $this->assertSame(1, $count);
    }

    protected function getRepository(): H5LinkRepository
    {
        return $this->repository;
    }

    protected function createNewEntity(): object
    {
        $coupon = new Coupon();
        $coupon->setName('测试优惠券');
        $coupon->setValid(true);
        $coupon->setExpireDay(30);

        $h5Link = new H5Link();
        $h5Link->setCoupon($coupon);
        $h5Link->setUrl('https://example.com/test_' . uniqid());

        return $h5Link;
    }
}
