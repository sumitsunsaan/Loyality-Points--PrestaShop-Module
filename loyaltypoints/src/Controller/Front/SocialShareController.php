<?php
namespace YourCompany\LoyaltyPoints\Controller\Front;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use PrestaShop\PrestaShop\Adapter\Product\ProductDataProvider;
use YourCompany\LoyaltyPoints\Service\PointsManager;

class SocialShareController extends AbstractController
{
    private $productDataProvider;
    private $pointsManager;

    public function __construct(
        ProductDataProvider $productDataProvider,
        PointsManager $pointsManager
    ) {
        $this->productDataProvider = $productDataProvider;
        $this->pointsManager = $pointsManager;
    }

    public function trackShareAction(Request $request): JsonResponse
    {
        $productId = $request->request->get('product_id');
        $platform = $request->request->get('platform');
        $account = $request->request->get('account');

        // Verify product exists
        $product = $this->productDataProvider->getProduct($productId);
        if (!$product->id) {
            return new JsonResponse(['error' => 'Invalid product'], 400);
        }

        // Check existing shares
        $existing = Db::getInstance()->getValue('
            SELECT COUNT(*) 
            FROM `'._DB_PREFIX_.'loyalty_social_shares`
            WHERE id_customer = '.(int)$this->context->customer->id.'
            AND id_product = '.(int)$productId.'
            AND platform = "'.pSQL($platform).'"
            AND social_account = "'.pSQL($account).'"
        ');

        if (!$existing) {
            Db::getInstance()->insert('loyalty_social_shares', [
                'id_customer' => $this->context->customer->id,
                'id_product' => $productId,
                'platform' => $platform,
                'social_account' => $account,
                'date_add' => date('Y-m-d H:i:s')
            ]);

            $this->pointsManager->addPoints(
                $this->context->customer,
                (int)\Configuration::get('LOYALTY_SOCIAL_POINTS'),
                'social_share'
            );
        }

        return new JsonResponse(['success' => true]);
    }
}