<?php

namespace Tourze\CouponH5LinkBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\CouponH5LinkBundle\Entity\H5Link;


/**
 * @method H5Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method H5Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method H5Link[]    findAll()
 * @method H5Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class H5LinkRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, H5Link::class);
    }
}
