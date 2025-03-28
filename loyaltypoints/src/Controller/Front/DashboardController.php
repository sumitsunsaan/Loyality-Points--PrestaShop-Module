<?php
namespace YourCompany\LoyaltyPoints\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PrestaShop\PrestaShop\Adapter\Customer\CustomerDataProvider;
use YourCompany\LoyaltyPoints\Service\PointsManager;

class DashboardController extends AbstractController
{
    private $customerDataProvider;
    private $pointsManager;

    public function __construct(
        CustomerDataProvider $customerDataProvider,
        PointsManager $pointsManager
    ) {
        $this->customerDataProvider = $customerDataProvider;
        $this->pointsManager = $pointsManager;
    }

    public function indexAction(): Response
    {
        $customer = $this->customerDataProvider->getCurrentCustomer();
        $pointsHistory = $this->pointsManager->getHistory($customer->getId());

        return $this->render('@Modules/loyaltypoints/views/templates/front/dashboard.twig', [
            'points' => $this->pointsManager->getCurrentPoints($customer->getId()),
            'history' => $pointsHistory
        ]);
    }
}