<?php
include_once(dirname(__FILE__).'/vpos_plugin.php');

class VPOSIntegrationValidationModuleFrontController extends
  ModuleFrontController
{

  public $ssl = true;
  public $display_column_left = false;

  private function afterVPOSProcess() {

    $vector = Configuration::get('VPOSI_VECTOR');

    //Llave Firma Publica de Alignet
    $llaveVPOSFirmaPub = Configuration::get('VPOSI_SIGN_ALI_KEY');

    //Llave Crypto Privada del Comercio
    $llaveComercioCryptoPriv = Configuration::get('VPOSI_ENC_PRIV_KEY');

    $arrayIn['IDACQUIRER'] = $_POST['IDACQUIRER'];
    $arrayIn['IDCOMMERCE'] = $_POST['IDCOMMERCE'];
    $arrayIn['XMLRES'] = $_POST['XMLRES'];
    $arrayIn['DIGITALSIGN'] = $_POST['DIGITALSIGN'];
    $arrayIn['SESSIONKEY'] = $_POST['SESSIONKEY'];
    $arrayOut = '';

    //Ejecución de Creación de Valores para la Solicitud de Interpretación de la Respuesta
    if(VPOSResponse($arrayIn,$arrayOut,$llaveVPOSFirmaPub,$llaveComercioCryptoPriv,$vector)){
      return $arrayOut;
    }else{
        return "Error durante el proceso de interpretación de la respuesta. "
        . "Verificar los componentes de seguridad: Vector Hexadecimal y Llaves.";
    }
  }

  public function initContent() {
    parent::initContent();

    $arrayOut = $this->afterVPOSProcess();

    $this->context->controller->addCSS(
      $this->_path.'views/css/vposintegration.css', 'all');

    if ($_GET['action'] == 'redirect') {
      $this->finalRedirect();
    } else {
      $this->setTemplate('validation.tpl');

      $extra_vars = array();
      $result = '';

      $cart = $this->context->cart;
      $total = (float)$cart->getOrderTotal(true, Cart::BOTH);
      $currency = $this->context->currency;
      $customer = new Customer($cart->id_customer);

  		if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
  			Tools::redirect('index.php?controller=order&step=1');

       $message = $this->authResult($arrayOut['authorizationResult']);

      if (!is_array($arrayOut) || $arrayOut['authorizationResult'] != '00'){
        $message = 'Hubo un error procesando la transaccion';
        $result = 'error';
      } else {

        $result = 'success';
        $this->module->validateOrder($cart->id, Configuration::get('PS_OS_PREPARATION'), $total,
        			$this->module->displayName, NULL, $extra_vars, (int)$currency->id, false, $customer->secure_key);
      }

      $this->context->smarty->assign(array(
        'message' => $message,
        'result' => $result,
        'auth_code' =>  $arrayOut['authorizationCode'],
        'auth_result' =>  $arrayOut['authorizationResult'],
        'error_code' => $arrayOut['errorCode'],
        'error_message' => $arrayOut['errorMessage'],
        'redirect_link' => $this->context->link->getModuleLink('vposintegration', 'validation', array('action' => 'redirect'), true)
      ));
    }


  }

  private function finalRedirect() {
    Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='.
  			$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key.'&result='.$vpos_result);
  }

  private function authResult($authCode){
    if ($authCode == '05') {
      return 'La transaccion fue rechazada';
    } elseif ($authCode == '01') {
      return 'La transaccion fue denegada';
    } else {
      return 'La transaccion fue exitosa';
    }
  }



  // public function postProcess() {
  //   $arrayOut = $this->afterVPOSProcess();
  //
  //   $vpos_result = 'success';
  //
  //   $cart = $this->context->cart;
	// 	if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
	// 		Tools::redirect('index.php?controller=order&step=1');
  //
	// 	$authorized = false;
	// 	foreach (Module::getPaymentModules() as $module)
	// 		if ($module['name'] == $this->module->name)
	// 			$authorized = true;
	// 	if (!$authorized)
	// 		 $vpos_result = 'error';
  //
  //   if (!is_array($arrayOut))
  //     $vpos_result = 'error';
  //
	// 	$customer = new Customer($cart->id_customer);
	// 	if (!Validate::isLoadedObject($customer))
	// 		Tools::redirect('index.php?controller=order&step=1');
  //
	// 	$currency = $this->context->currency;
	// 	$total = (float)$cart->getOrderTotal(true, Cart::BOTH);
  //   $extra_vars = array();
  //
  //   if ($vpos_result == 'success')
  // 		$this->module->validateOrder($cart->id, Configuration::get('PS_OS_PREPARATION'), $total,
  // 			$this->module->displayName, NULL, $extra_vars, (int)$currency->id, false, $customer->secure_key);
  //
	// 	Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='.
	// 		$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key.'&result='.$vpos_result);
  // }
}
