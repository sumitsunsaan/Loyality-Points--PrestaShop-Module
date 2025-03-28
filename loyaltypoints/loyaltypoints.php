<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class LoyaltyPoints extends Module
{
    public function __construct()
    {
        $this->name = 'loyaltypoints';
        $this->tab = 'customer_engagement';
        $this->version = '1.0.0';
        $this->author = 'Your Company';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Loyalty Points');
        $this->description = $this->l('Advanced loyalty points system');
    }

    public function install()
    {
        return parent::install() &&
            $this->installDatabase() &&
            $this->registerHooks();
    }

    private function installDatabase()
    {
        $sql = [];
        include(dirname(__FILE__).'/sql/install.php');
        foreach ($sql as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }
        return true;
    }

    private function registerHooks()
    {
        return $this->registerHook([
            'actionCustomerAccountAdd',
            'actionOrderStatusPostUpdate',
            'displayCustomerAccount',
            'displayHeader',
            'actionObjectCustomerUpdateAfter'
        ]);
    }

    // Basic hook implementations
    public function hookDisplayCustomerAccount($params)
    {
        return $this->display(__FILE__, 'account-link.tpl');
    }
}