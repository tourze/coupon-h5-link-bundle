# 优惠券 H5 链接包

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)](https://php.net)
[![Symfony Version](https://img.shields.io/badge/symfony-%5E7.3-green)](https://symfony.com)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](https://github.com/tourze/php-monorepo)
[![Coverage Status](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://github.com/tourze/php-monorepo)

[English](README.md) | [中文](README.zh-CN.md)

一个用于管理 H5 优惠券外链的 Symfony 包，提供 H5 链接操作的实体管理和仓储功能。

## 特性

- H5 外链实体管理
- 与优惠券核心系统集成
- 内置时间戳、责任人和 IP 追踪
- API 序列化支持
- Doctrine ORM 集成

## 安装

通过 Composer 安装包：

```bash
composer require tourze/coupon-h5-link-bundle
```

## 快速开始

### 1. 注册包

在你的 `config/bundles.php` 中添加包：

```php
return [
    // ...
    Tourze\CouponH5LinkBundle\CouponH5LinkBundle::class => ['all' => true],
];
```

### 2. 创建和配置 H5 链接实体

```php
use Tourze\CouponH5LinkBundle\Entity\H5Link;
use Tourze\CouponCoreBundle\Entity\Coupon;

// 创建新的 H5 链接
$h5Link = new H5Link();
$h5Link->setUrl('https://example.com/coupon-page');
$h5Link->setCoupon($coupon); // 与优惠券关联

// 保存到数据库
$entityManager->persist($h5Link);
$entityManager->flush();
```

### 3. 使用仓储

```php
use Tourze\CouponH5LinkBundle\Repository\H5LinkRepository;

// 注入仓储
public function __construct(
    private H5LinkRepository $h5LinkRepository
) {}

// 查找 H5 链接
$h5Links = $this->h5LinkRepository->findAll();
```

### 4. API 序列化

H5Link 实体实现了 `ApiArrayInterface`，便于 API 响应：

```php
$h5Link = new H5Link();
$h5Link->setUrl('https://example.com/coupon');

// 获取 API 数组表示
$apiData = $h5Link->retrieveApiArray();
// 返回: [
//     'id' => 12345,
//     'createTime' => '2024-01-01 12:00:00',
//     'updateTime' => '2024-01-01 12:00:00',
//     'url' => 'https://example.com/coupon'
// ]
```

## 高级用法

### 自定义仓储方法

扩展 H5LinkRepository 来添加自定义查询方法：

```php
use Tourze\CouponH5LinkBundle\Repository\H5LinkRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomH5LinkRepository extends H5LinkRepository
{
    public function findByCouponType(string $type): array
    {
        return $this->createQueryBuilder('h')
            ->join('h.coupon', 'c')
            ->where('c.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }
}
```

### 验证和安全

H5Link 实体包含内置验证约束：

```php
// URL 验证确保格式正确
// 长度约束防止数据库溢出
// NotBlank 确保必填字段已填写

// 可以通过事件或自定义约束添加自定义验证
```

### 事件集成

监听 H5Link 实体变更：

```php
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Tourze\CouponH5LinkBundle\Entity\H5Link;

#[AsEntityListener(event: Events::prePersist, entity: H5Link::class)]
class H5LinkListener
{
    public function prePersist(H5Link $h5Link): void
    {
        // 保存前的自定义逻辑
        $this->validateUrl($h5Link->getUrl());
    }
}
```

## 配置

包会自动配置必要的服务。无需额外配置。

## 实体特性

### H5Link 实体

`H5Link` 实体提供：

- **id**: 雪花 ID 用于唯一标识
- **url**: H5 外链 URL
- **coupon**: 与优惠券实体的一对一关系
- **createTime/updateTime**: 自动时间戳
- **createdBy/updatedBy**: 用户追踪
- **createIp/updateIp**: IP 地址追踪

### 使用的特性

- `SnowflakeKeyAware`: 提供基于雪花的 ID 生成
- `TimestampableAware`: 自动创建和更新时间戳
- `BlameableAware`: 追踪创建/更新实体的用户
- `IpTraceableAware`: 追踪创建/更新的 IP 地址

## 要求

- PHP 8.1 或更高版本
- Symfony 7.3 或更高版本
- Doctrine ORM 3.0 或更高版本

## 许可证

此包在 MIT 许可证下发布。详情请参阅 [LICENSE](LICENSE) 文件。