<?php
namespace YourCompany\LoyaltyPoints\Service;

use Db;
use Customer;
use Configuration;

class TierManager
{
    private $pointsRepository;

    public function __construct(PointsRepository $pointsRepository)
    {
        $this->pointsRepository = $pointsRepository;
    }

    public function updateCustomerTiers(): void
    {
        $tiers = $this->getTierThresholds();
        
        $customers = Db::getInstance()->executeS('
            SELECT lp.id_customer, lp.points
            FROM `'._DB_PREFIX_.'loyalty_points` lp
            LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer = lp.id_customer)
            WHERE c.deleted = 0
        ');

        foreach ($customers as $customerData) {
            $currentTier = $this->calculateTier($customerData['points'], $tiers);
            Db::getInstance()->update('customer', [
                'loyalty_tier' => pSQL($currentTier)
            ], 'id_customer = '.(int)$customerData['id_customer']);
        }
    }

    private function getTierThresholds(): array
    {
        return [
            'bronze' => (int)Configuration::get('LOYALTY_TIER_BRONZE'),
            'silver' => (int)Configuration::get('LOYALTY_TIER_SILVER'),
            'gold' => (int)Configuration::get('LOYALTY_TIER_GOLD')
        ];
    }

    private function calculateTier(int $points, array $tiers): string
    {
        if ($points >= $tiers['gold']) return 'gold';
        if ($points >= $tiers['silver']) return 'silver';
        if ($points >= $tiers['bronze']) return 'bronze';
        return 'none';
    }
}