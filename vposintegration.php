<?php
  class VPOSIntegration extends PaymentModule
  {
    public function __construct()
    {
       $this->name = 'vposintegration';
       $this->tab = 'payments_gateways';
       $this->version = '1.0.0';
       $this->author = 'Luis Cubillo';
       $this->need_instance = 1;
      //  $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
       $this->bootstrap = true;

       parent::__construct();

       $this->displayName = $this->l('Tarjeta Credito/Debito');
       $this->description = $this->l('Modulo para pago electronico utilizando VPOS');

       $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

     }


     public function install()
     {
       if (!parent::install() ||
           !$this->registerHook('displayPayment') ||
           !$this->registerHook('displayPaymentReturn'))
              return false;
        return true;
     }

    public function uninstall()
    {
      if (!parent::uninstall() ||
          !Configuration::deleteByName('VPOSI_ENVIRONMENT') ||
          !Configuration::deleteByName('VPOSI_ID_ACQUIRER') ||
          !Configuration::deleteByName('VPOSI_ID_ACQUIRER') ||
          !Configuration::deleteByName('VPOSI_ID_COMMERCE') ||
          !Configuration::deleteByName('VPOSI_TERMINAL_CODE') ||
          !Configuration::deleteByName('VPOSI_ID_MALL') ||
          !Configuration::deleteByName('VPOSI_VECTOR') ||
          !Configuration::deleteByName('VPOSI_SIGN_PRIV_KEY') ||
          !Configuration::deleteByName('VPOSI_SIGN_PUB_KEY') ||
          !Configuration::deleteByName('VPOSI_ENC_PRIV_KEY') ||
          !Configuration::deleteByName('VPOSI_ENC_PUB_KEY')
      )
        return false;
      return true;
    }

    public function assigConfiguration() {
      $environment = Configuration::get('VPOSI_ENVIRONMENT');
      $id_adquirer = Configuration::get('VPOSI_ID_ACQUIRER');
      $id_commerce = Configuration::get('VPOSI_ID_COMMERCE');
      $terminal_code = Configuration::get('VPOSI_TERMINAL_CODE');
      $id_mall = Configuration::get('VPOSI_ID_MALL');
      $vector = Configuration::get('VPOSI_VECTOR');
      $sign_priv_key = Configuration::get('VPOSI_SIGN_PRIV_KEY');
      $sign_pub_key = Configuration::get('VPOSI_SIGN_PUB_KEY');
      $enc_priv_key = Configuration::get('VPOSI_ENC_PRIV_KEY');
      $enc_pub_key = Configuration::get('VPOSI_ENC_PUB_KEY');

      $this->context->smarty->assign('environment', $environment);
      $this->context->smarty->assign('id_adquirer', $id_adquirer);
      $this->context->smarty->assign('id_commerce', $id_commerce);
      $this->context->smarty->assign('terminal_code', $terminal_code);
      $this->context->smarty->assign('id_mall', $id_mall);
      $this->context->smarty->assign('vector', $vector);
      $this->context->smarty->assign('sign_priv_key', $sign_priv_key);
      $this->context->smarty->assign('sign_pub_key', $sign_pub_key);
      $this->context->smarty->assign('enc_priv_key', $enc_priv_key);
      $this->context->smarty->assign('enc_pub_key', $enc_pub_key);
    }


    public function processConfiguration() {
      if (Tools::isSubmit('vpos_pago'))
      {
        $environment = Tools::getValue('environment');
        $id_adquirer = Tools::getValue('id_adquirer');
        $id_commerce = Tools::getValue('id_commerce');
        $terminal_code = Tools::getValue('environment');
        $id_mall = Tools::getValue('id_mall');
        $vector = Tools::getValue('vector');
        $sign_priv_key = Tools::getValue('sign_priv_key');
        $sign_pub_key = Tools::getValue('sign_pub_key');
        $enc_priv_key = Tools::getValue('enc_priv_key');
        $enc_pub_key = Tools::getValue('enc_pub_key');

        Configuration::updateValue('VPOSI_ENVIRONMENT', $environment);
        Configuration::updateValue('VPOSI_ID_ACQUIRER', $id_adquirer);
        Configuration::updateValue('VPOSI_ID_COMMERCE', $id_commerce);
        Configuration::updateValue('VPOSI_TERMINAL_CODE', $terminal_code);
        Configuration::updateValue('VPOSI_ID_MALL', $id_mall);
        Configuration::updateValue('VPOSI_VECTOR', $vector);
        Configuration::updateValue('VPOSI_SIGN_PRIV_KEY', $sign_priv_key);
        Configuration::updateValue('VPOSI_SIGN_PUB_KEY', $sign_pub_key);
        Configuration::updateValue('VPOSI_ENC_PRIV_KEY', $enc_priv_key);
        Configuration::updateValue('VPOSI_ENC_PUB_KEY', $enc_pub_key);

        $this->context->smarty->assign('confirmation', ok);

      }
    }

    public function getHookController($hook_name)
  	{
  		require_once(dirname(__FILE__).'/controllers/hook/'. $hook_name.'.php');
  		$controller_name = $this->name.$hook_name.'Controller';
  		$controller = new $controller_name($this, __FILE__, $this->_path);
  		return $controller;
  	}

    public function getContent()
    {
      $this->processConfiguration();
      $this->assigConfiguration();
      return $this->display(__FILE__, 'getContent.tpl');
    }

    public function hookDisplayPayment($params)
    {
      $controller = $this->getHookController('displayPayment');
      return $controller->run($params);

    }
  }
 ?>
