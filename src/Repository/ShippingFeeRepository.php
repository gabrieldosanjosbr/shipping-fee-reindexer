<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ShippingFee;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ShippingFeeRepository extends ServiceEntityRepository
{
    /**
     * ProviderRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingFee::class);
    }

}
