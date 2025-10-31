<?php

declare(strict_types=1);

namespace Tourze\CouponH5LinkBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\ApiArrayInterface;
use Tourze\CouponCoreBundle\Entity\Coupon;
use Tourze\CouponH5LinkBundle\Repository\H5LinkRepository;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

/**
 * @implements ApiArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: H5LinkRepository::class)]
#[ORM\Table(name: 'coupon_h5_link', options: ['comment' => 'H5外链'])]
class H5Link implements ApiArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;
    use IpTraceableAware;

    #[Ignore]
    #[ORM\OneToOne(targetEntity: Coupon::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Coupon $coupon = null;

    #[Groups(groups: ['restful_read'])]
    #[ORM\Column(type: Types::TEXT, options: ['comment' => 'H5链接'])]
    #[Assert\NotBlank]
    #[Assert\Url]
    #[Assert\Length(max: 65535)]
    private ?string $url = null;

    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    public function setCoupon(Coupon $coupon): void
    {
        $this->coupon = $coupon;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveApiArray(): array
    {
        return [
            'id' => $this->getId(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
            'url' => $this->getUrl(),
        ];
    }

    public function __toString(): string
    {
        return sprintf('H5Link#%s', $this->getId() ?? 'new');
    }
}
