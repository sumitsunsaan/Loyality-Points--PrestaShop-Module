<?php
namespace YourCompany\LoyaltyPoints\Service;

use Db;
use Customer;
use Validate;
use Configuration;

class BirthdayManager
{
    private $pointsManager;

    public function __construct(PointsManager $pointsManager)
    {
        $this->pointsManager = $pointsManager;
    }

    public function checkBirthdayRewards(): void
    {
        $today = date('m-d');
        $customers = Db::getInstance()->executeS('
            SELECT id_customer 
            FROM `'._DB_PREFIX_.'customer`
            WHERE DATE_FORMAT(birthday, "%m-%d") = "'.pSQL($today).'"
            AND birthday IS NOT NULL
        ');

        foreach ($customers as $customerData) {
            $customer = new Customer($customerData['id_customer']);
            if (!Validate::isLoadedObject($customer)) {
                continue;
            }

            $hasReward = Db::getInstance()->getValue('
                SELECT COUNT(*) 
                FROM `'._DB_PREFIX_.'loyalty_points_history`
                WHERE id_customer = '.(int)$customer->id.'
                AND source = "birthday"
                AND YEAR(date_add) = YEAR(NOW())
            ');

            if (!$hasReward) {
                $this->pointsManager->addPoints(
                    $customer,
                    (int)Configuration::get('LOYALTY_BIRTHDAY_POINTS'),
                    'birthday'
                );
            }
        }
    }
}