<?php
namespace YourCompany\LoyaltyPoints\Repository;

use Doctrine\ORM\EntityRepository;
use YourCompany\LoyaltyPoints\Entity\LoyaltyPoints;

class PointsRepository extends EntityRepository
{
    public function findOneByCustomerId(int $customerId): ?LoyaltyPoints
    {
        return $this->findOneBy(['customerId' => $customerId]);
    }

    public function getHistory(int $customerId): array
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT h 
                FROM YourCompany\LoyaltyPoints\Entity\LoyaltyPointsHistory h
                WHERE h.customerId = :customerId
                ORDER BY h.dateAdd DESC
            ')
            ->setParameter('customerId', $customerId)
            ->getResult();
    }
}