<?php
namespace YourCompany\LoyaltyPoints\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ps_loyalty_points")
 * @ORM\Entity(repositoryClass="YourCompany\LoyaltyPoints\Repository\PointsRepository")
 */
class LoyaltyPoints
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_loyalty_point", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="id_customer", type="integer")
     */
    private $customerId;

    /**
     * @ORM\Column(name="points", type="integer")
     */
    private $points;

    /**
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdded;

    /**
     * @ORM\Column(name="date_upd", type="datetime")
     */
    private $dateUpdated;

    // Getters and setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;
        return $this;
    }

    // Add remaining getters/setters
}