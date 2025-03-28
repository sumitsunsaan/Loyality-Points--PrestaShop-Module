<?php
namespace YourCompany\LoyaltyPoints\Service;

use Mail;
use Customer;

class EmailNotifier
{
    public function sendPointsEarnedEmail(Customer $customer, int $points, string $source): bool
    {
        return Mail::send(
            $customer->id_lang,
            'points_earned',
            $this->l('You earned loyalty points'),
            [
                '{firstname}' => $customer->firstname,
                '{lastname}' => $customer->lastname,
                '{points}' => $points,
                '{source}' => $source,
                '{balance}' => $this->getCurrentBalance($customer->id),
                '{date}' => date('Y-m-d H:i'),
            ],
            $customer->email,
            null,
            null,
            null,
            null,
            null,
            _PS_MODULE_DIR_.'loyaltypoints/mails/'
        );
    }

    public function sendBirthdayRewardEmail(Customer $customer, int $points): bool
    {
        return Mail::send(
            $customer->id_lang,
            'birthday_reward',
            $this->l('Happy Birthday Reward!'),
            [
                '{firstname}' => $customer->firstname,
                '{lastname}' => $customer->lastname,
                '{points}' => $points,
                '{balance}' => $this->getCurrentBalance($customer->id),
            ],
            $customer->email,
            null,
            null,
            null,
            null,
            null,
            _PS_MODULE_DIR_.'loyaltypoints/mails/'
        );
    }

    private function getCurrentBalance(int $customerId): int
    {
        // Implement balance retrieval from repository
    }
}