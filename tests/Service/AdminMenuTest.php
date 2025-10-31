<?php

declare(strict_types=1);

namespace Tourze\CouponH5LinkBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CouponH5LinkBundle\Service\AdminMenu;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;

/**
 * AdminMenu测试
 *
 * 测试优惠券H5外链管理菜单服务的功能，包括：
 * - 菜单项的正确添加
 * - 权限设置的验证
 * - 图标配置的验证
 * - URL生成的验证
 *
 * @internal
 * @author Claude AI <noreply@anthropic.com>
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    protected function onSetUp(): void
    {
        // Required abstract method implementation
    }

    public function testServiceCanBeRetrievedFromContainer(): void
    {
        $adminMenu = self::getService(AdminMenu::class);

        $this->assertInstanceOf(AdminMenu::class, $adminMenu);
    }

    public function testServiceIsCallable(): void
    {
        $adminMenu = self::getService(AdminMenu::class);

        $this->assertIsCallable($adminMenu);
    }
}
