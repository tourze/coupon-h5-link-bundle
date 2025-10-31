<?php

namespace Tourze\CouponH5LinkBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Tourze\CouponH5LinkBundle\DependencyInjection\CouponH5LinkExtension;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(CouponH5LinkExtension::class)]
final class CouponH5LinkExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private CouponH5LinkExtension $extension;

    private ContainerBuilder $container;

    public function testIsInstanceOfExtension(): void
    {
        $this->assertInstanceOf(Extension::class, $this->extension);
    }

    public function testLoadWithEmptyConfig(): void
    {
        $configs = [[]];

        $this->extension->load($configs, $this->container);

        $this->assertInstanceOf(ContainerBuilder::class, $this->container);
    }

    public function testLoadWithMultipleConfigs(): void
    {
        $configs = [[], []];

        $this->extension->load($configs, $this->container);

        $this->assertInstanceOf(ContainerBuilder::class, $this->container);
    }

    public function testGetAlias(): void
    {
        $alias = $this->extension->getAlias();
        $this->assertSame('coupon_h5_link', $alias);
    }

    public function testServicesYamlFileExists(): void
    {
        $servicesFile = dirname(__DIR__, 2) . '/src/Resources/config/services.yaml';
        $this->assertFileExists($servicesFile);
    }

    public function testFileLocatorPath(): void
    {
        $expectedPath = dirname(__DIR__, 2) . '/src/Resources/config';
        $this->assertDirectoryExists($expectedPath);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new CouponH5LinkExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }
}
