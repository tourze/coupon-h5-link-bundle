<?php

declare(strict_types=1);

namespace Tourze\CouponH5LinkBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\CouponCoreBundle\CouponCoreBundle;

class CouponH5LinkBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            CouponCoreBundle::class => ['all' => true],
        ];
    }
}
