<?php
namespace YourCompany\LoyaltyPoints\Service;

use Customer;
use DateTime;

class TransferValidator
{
    private $fraudDetector;

    public function __construct(FraudDetector $fraudDetector)
    {
        $this->fraudDetector = $fraudDetector;
    }

    public function validateTransfer(Customer $sender, Customer $receiver, int $points): array
    {
        $errors = [];

        if ($sender->id === $receiver->id) {
            $errors[] = 'Cannot transfer to yourself';
        }

        if ($points <= 0) {
            $errors[] = 'Invalid points amount';
        }

        if ($this->fraudDetector->isSuspiciousTransfer($sender->id, $points)) {
            $errors[] = 'Transfer requires manual verification';
        }

        return $errors;
    }
}