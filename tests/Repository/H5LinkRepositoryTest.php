<?php

namespace Tourze\CouponH5LinkBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Tourze\CouponH5LinkBundle\Entity\H5Link;
use Tourze\CouponH5LinkBundle\Repository\H5LinkRepository;

class H5LinkRepositoryTest extends TestCase
{
    private H5LinkRepository $repository;
    private ManagerRegistry $registry;

    public function testIsInstanceOfServiceEntityRepository(): void
    {
        $this->assertInstanceOf(ServiceEntityRepository::class, $this->repository);
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(H5LinkRepository::class, $this->repository);
    }

    public function testEntityClass(): void
    {
        $expectedEntityClass = H5Link::class;

        $reflection = new \ReflectionClass($this->repository);
        $constructor = $reflection->getConstructor();
        $constructorParams = $constructor->getParameters();

        $this->assertCount(1, $constructorParams);
        $this->assertSame('registry', $constructorParams[0]->getName());
    }

    public function testDocblockMethods(): void
    {
        $reflection = new \ReflectionClass($this->repository);
        $docComment = $reflection->getDocComment();

        $this->assertNotFalse($docComment);
        $this->assertStringContainsString('@method H5Link|null find($id, $lockMode = null, $lockVersion = null)', $docComment);
        $this->assertStringContainsString('@method H5Link|null findOneBy(array $criteria, array $orderBy = null)', $docComment);
        $this->assertStringContainsString('@method H5Link[]    findAll()', $docComment);
        $this->assertStringContainsString('@method H5Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)', $docComment);
    }

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new H5LinkRepository($this->registry);
    }
}