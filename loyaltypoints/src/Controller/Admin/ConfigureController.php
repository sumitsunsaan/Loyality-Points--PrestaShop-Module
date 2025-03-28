<?php
namespace YourCompany\LoyaltyPoints\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Configuration;

class ConfigureController extends FrameworkBundleAdminController
{
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('points_per_currency', NumberType::class, [
                'label' => 'Points per currency unit',
                'data' => Configuration::get('LOYALTY_POINTS_RATE')
            ])
            ->add('social_media_points', NumberType::class, [
                'label' => 'Social Media Share Points',
                'data' => Configuration::get('LOYALTY_SOCIAL_POINTS')
            ])
            ->add('tier_bronze', IntegerType::class, [
                'label' => 'Bronze Tier Threshold',
                'data' => Configuration::get('LOYALTY_TIER_BRONZE')
            ])
            ->add('tier_silver', IntegerType::class, [
                'label' => 'Silver Tier Threshold',
                'data' => Configuration::get('LOYALTY_TIER_SILVER')
            ])
            ->add('tier_gold', IntegerType::class, [
                'label' => 'Gold Tier Threshold',
                'data' => Configuration::get('LOYALTY_TIER_GOLD')
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Settings',
                'attr' => ['class' => 'btn btn-primary']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            Configuration::updateValue('LOYALTY_POINTS_RATE', $data['points_per_currency']);
            Configuration::updateValue('LOYALTY_SOCIAL_POINTS', $data['social_media_points']);
            Configuration::updateValue('LOYALTY_TIER_BRONZE', $data['tier_bronze']);
            Configuration::updateValue('LOYALTY_TIER_SILVER', $data['tier_silver']);
            Configuration::updateValue('LOYALTY_TIER_GOLD', $data['tier_gold']);
            $this->addFlash('success', 'Settings updated successfully');
        }

        return $this->render('@Modules/loyaltypoints/views/templates/admin/configure.twig', [
            'form' => $form->createView()
        ]);
    }
}