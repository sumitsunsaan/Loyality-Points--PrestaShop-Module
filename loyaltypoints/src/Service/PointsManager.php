<?php
namespace YourCompany\LoyaltyPoints\Service;

use Doctrine\ORM\EntityManagerInterface;
use YourCompany\LoyaltyPoints\Entity\LoyaltyPoints;
use Customer;
use Context;
use Db;

class PointsManager
{
    private $entityManager;
    private $pointsRepository;
    private $context;

    public function __construct(
        EntityManagerInterface $entityManager,
        PointsRepository $pointsRepository
    ) {
        $this->entityManager = $entityManager;
        $this->pointsRepository = $pointsRepository;
        $this->context = Context::getContext();
    }

    public function addPoints(Customer $customer, int $points, string $source): void
    {
        $loyaltyAccount = $this->pointsRepository->findOneByCustomerId($customer->id);
        
        if (!$loyaltyAccount) {
            $loyaltyAccount = new LoyaltyPoints();
            $loyaltyAccount->setCustomerId($customer->id)
                ->setPoints(0)
                ->setDateAdded(new \DateTime())
                ->setDateUpdated(new \DateTime());
        }

        $currentPoints = $loyaltyAccount->getPoints();
        $loyaltyAccount->setPoints($currentPoints + $points)
            ->setDateUpdated(new \DateTime());
        
        $this->entityManager->persist($loyaltyAccount);
        $this->entityManager->flush();
        
        $this->recordTransaction($customer->id, $points, 'earn', $source);
    }

    public function transferPoints(Customer $sender, Customer $receiver, int $points): bool
    {
        $senderAccount = $this->pointsRepository->findOneByCustomerId($sender->id);
        
        if (!$senderAccount || $senderAccount->getPoints() < $points) {
            return false;
        }

        // Deduct from sender
        $senderAccount->setPoints($senderAccount->getPoints() - $points)
            ->setDateUpdated(new \DateTime());
        $this->entityManager->persist($senderAccount);

        // Add to receiver
        $receiverAccount = $this->pointsRepository->findOneByCustomerId($receiver->id);
        if (!$receiverAccount) {
            $receiverAccount = new LoyaltyPoints();
            $receiverAccount->setCustomerId($receiver->id)
                ->setPoints(0)
                ->setDateAdded(new \DateTime())
                ->setDateUpdated(new \DateTime());
        }
        $receiverAccount->setPoints($receiverAccount->getPoints() + $points)
            ->setDateUpdated(new \DateTime());
        $this->entityManager->persist($receiverAccount);

        $this->entityManager->flush();

        $this->recordTransaction($sender->id, -$points, 'transfer', 'Outgoing transfer to #'.$receiver->id);
        $this->recordTransaction($receiver->id, $points, 'transfer', 'Incoming transfer from #'.$sender->id);

        return true;
    }

    private function recordTransaction(int $customerId, int $points, string $type, string $source): void
    {
        Db::getInstance()->insert('loyalty_points_history', [
            'id_customer' => $customerId,
            'points' => $points,
            'type' => $type,
            'source' => $source,
            'date_add' => date('Y-m-d H:i:s')
        ]);
    }
}