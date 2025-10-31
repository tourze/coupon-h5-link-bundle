<?php

declare(strict_types=1);

namespace Tourze\CouponH5LinkBundle\Service;

use Knp\Menu\ItemInterface;
use Tourze\CouponH5LinkBundle\Entity\H5Link;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;

/**
 * 优惠券H5外链管理菜单服务
 *
 * 为EasyAdmin后台管理界面提供优惠券H5外链相关功能的菜单结构。
 * 集成到优惠券管理菜单下，提供H5外链的管理入口。
 *
 * @author Claude AI <noreply@anthropic.com>
 */
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        // 查找或创建优惠券管理顶级菜单
        if (null === $item->getChild('优惠券管理')) {
            $item->addChild('优惠券管理')
                ->setExtra('permission', 'CouponBundle')
                ->setExtra('icon', 'fas fa-ticket-alt')
            ;
        }

        $couponMenu = $item->getChild('优惠券管理');
        if (null !== $couponMenu) {
            // H5外链管理
            $couponMenu
                ->addChild('H5外链管理')
                ->setUri($this->linkGenerator->getCurdListPage(H5Link::class))
                ->setExtra('icon', 'fas fa-link')
                ->setExtra('permission', 'H5Link:ROLE_ADMIN')
            ;
        }
    }
}
