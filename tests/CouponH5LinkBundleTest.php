<?php

declare(strict_types=1);

namespace CouponH5LinkBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CouponH5LinkBundle\CouponH5LinkBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(CouponH5LinkBundle::class)]
#[RunTestsInSeparateProcesses]
final class CouponH5LinkBundleTest extends AbstractBundleTestCase
{
}
