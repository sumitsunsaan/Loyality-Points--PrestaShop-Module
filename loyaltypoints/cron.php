<?php
require_once __DIR__.'/config/config.inc.php';
require_once __DIR__.'/loyaltypoints.php';

$module = Module::getInstanceByName('loyaltypoints');

// Daily cron tasks
if ($module->active) {
    // Handle birthday rewards
    $birthdayManager = $module->get('yourcompany.loyaltypoints.birthday_manager');
    $birthdayManager->checkBirthdayRewards();

    // Handle points expiration
    $pointsManager = $module->get('yourcompany.loyaltypoints.points_manager');
    $pointsManager->expireOldPoints();

    // Handle tier upgrades
    $tierManager = $module->get('yourcompany.loyaltypoints.tier_manager');
    $tierManager->updateCustomerTiers();
}