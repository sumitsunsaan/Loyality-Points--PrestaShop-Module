<?php
namespace YourCompany\LoyaltyPoints\Service;

use Db;

class FraudDetector
{
    public function isSuspiciousTransfer(int $customerId, int $points): bool
    {
        $transfersLast24h = Db::getInstance()->getValue('
            SELECT SUM(points) 
            FROM `'._DB_PREFIX_.'loyalty_points_history`
            WHERE id_customer = '.(int)$customerId.'
            AND type = "transfer"
            AND date_add >= DATE_SUB(NOW(), INTERVAL 1 DAY)
        ');

        $totalPoints = Db::getInstance()->getValue('
            SELECT points 
            FROM `'._DB_PREFIX_.'loyalty_points`
            WHERE id_customer = '.(int)$customerId
        );

        // Check if transfer exceeds 50% of total points
        if ($points > ($totalPoints * 0.5)) {
            return true;
        }

        // Check if transfers exceed 1000 points in 24h
        if (($transfersLast24h + $points) > 1000) {
            return true;
        }

        return false;
    }
}