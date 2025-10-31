# Coupon H5 Link Bundle

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)](https://php.net)
[![Symfony Version](https://img.shields.io/badge/symfony-%5E7.3-green)](https://symfony.com)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](https://github.com/tourze/php-monorepo)
[![Coverage Status](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://github.com/tourze/php-monorepo)

[English](README.md) | [中文](README.zh-CN.md)

A Symfony bundle for managing H5 coupon external links, providing entity management 
and repository functionality for H5 link operations.

## Features

- H5 external link entity management
- Integration with coupon core system
- Built-in timestamp, blame, and IP tracking
- API serialization support
- Doctrine ORM integration

## Installation

Install the package via Composer:

```bash
composer require tourze/coupon-h5-link-bundle
```

## Quick Start

### 1. Register the Bundle

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    Tourze\CouponH5LinkBundle\CouponH5LinkBundle::class => ['all' => true],
];
```

### 2. Create and Configure H5 Link Entity

```php
use Tourze\CouponH5LinkBundle\Entity\H5Link;
use Tourze\CouponCoreBundle\Entity\Coupon;

// Create a new H5 link
$h5Link = new H5Link();
$h5Link->setUrl('https://example.com/coupon-page');
$h5Link->setCoupon($coupon); // Associate with a coupon

// Save to database
$entityManager->persist($h5Link);
$entityManager->flush();
```

### 3. Using the Repository

```php
use Tourze\CouponH5LinkBundle\Repository\H5LinkRepository;

// Inject the repository
public function __construct(
    private H5LinkRepository $h5LinkRepository
) {}

// Find H5 links
$h5Links = $this->h5LinkRepository->findAll();
```

### 4. API Serialization

The H5Link entity implements `ApiArrayInterface` for easy API responses:

```php
$h5Link = new H5Link();
$h5Link->setUrl('https://example.com/coupon');

// Get API array representation
$apiData = $h5Link->retrieveApiArray();
// Returns: [
//     'id' => 12345,
//     'createTime' => '2024-01-01 12:00:00',
//     'updateTime' => '2024-01-01 12:00:00',
//     'url' => 'https://example.com/coupon'
// ]
```

## Advanced Usage

### Custom Repository Methods

Extend the H5LinkRepository to add custom query methods:

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

### Validation and Security

The H5Link entity includes built-in validation constraints:

```php
// URL validation ensures proper format
// Length constraint prevents database overflow
// NotBlank ensures required field is filled

// Custom validation can be added via events or custom constraints
```

### Event Integration

Listen to H5Link entity changes:

```php
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Tourze\CouponH5LinkBundle\Entity\H5Link;

#[AsEntityListener(event: Events::prePersist, entity: H5Link::class)]
class H5LinkListener
{
    public function prePersist(H5Link $h5Link): void
    {
        // Custom logic before saving
        $this->validateUrl($h5Link->getUrl());
    }
}
```

## Configuration

The bundle automatically configures the necessary services. No additional configuration is required.

## Entity Features

### H5Link Entity

The `H5Link` entity provides:

- **id**: Snowflake ID for unique identification
- **url**: The H5 external link URL
- **coupon**: One-to-one relationship with Coupon entity
- **createTime/updateTime**: Automatic timestamps
- **createdBy/updatedBy**: User tracking
- **createIp/updateIp**: IP address tracking

### Traits Used

- `SnowflakeKeyAware`: Provides snowflake-based ID generation
- `TimestampableAware`: Automatic creation and update timestamps
- `BlameableAware`: Track user who created/updated the entity
- `IpTraceableAware`: Track IP addresses for creation/updates

## Requirements

- PHP 8.1 or higher
- Symfony 7.3 or higher
- Doctrine ORM 3.0 or higher

## License

This bundle is released under the MIT License. See the [LICENSE](LICENSE) file for details.