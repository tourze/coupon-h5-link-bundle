<?php

declare(strict_types=1);

namespace Tourze\CouponH5LinkBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use Tourze\CouponCoreBundle\DataFixtures\CouponFixtures;
use Tourze\CouponCoreBundle\Entity\Coupon;
use Tourze\CouponH5LinkBundle\Entity\H5Link;

#[When(env: 'test')]
#[When(env: 'dev')]
class H5LinkFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const H5_LINK_BASIC_DISCOUNT = 'h5-link-basic-discount';
    public const H5_LINK_SHORT_TERM = 'h5-link-short-term';
    public const H5_LINK_NEED_ACTIVE = 'h5-link-need-active';

    public function load(ObjectManager $manager): void
    {
        $basicDiscountCoupon = $this->getReference(CouponFixtures::COUPON_BASIC_DISCOUNT, Coupon::class);
        $shortTermCoupon = $this->getReference(CouponFixtures::COUPON_SHORT_TERM, Coupon::class);
        $needActiveCoupon = $this->getReference(CouponFixtures::COUPON_NEED_ACTIVE, Coupon::class);

        $h5Link1 = new H5Link();
        $h5Link1->setCoupon($basicDiscountCoupon);
        $h5Link1->setUrl('https://coupon.test/h5/basic-discount');
        $manager->persist($h5Link1);

        $h5Link2 = new H5Link();
        $h5Link2->setCoupon($shortTermCoupon);
        $h5Link2->setUrl('https://coupon.test/h5/short-term');
        $manager->persist($h5Link2);

        $h5Link3 = new H5Link();
        $h5Link3->setCoupon($needActiveCoupon);
        $h5Link3->setUrl('https://coupon.test/h5/need-active');
        $manager->persist($h5Link3);

        $manager->flush();

        $this->addReference(self::H5_LINK_BASIC_DISCOUNT, $h5Link1);
        $this->addReference(self::H5_LINK_SHORT_TERM, $h5Link2);
        $this->addReference(self::H5_LINK_NEED_ACTIVE, $h5Link3);
    }

    public function getDependencies(): array
    {
        return [
            CouponFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['coupon', 'test'];
    }
}
