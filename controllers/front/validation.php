<?php
include_once(dirname(__FILE__).'/vpos_plugin.php');

class VPOSIntegrationValidationModuleFrontController extends
  ModuleFrontController
{

  public $ssl = true;
  public $display_column_left = false;

  private function afterVPOSProcess() {

    $vector = "A3C716F811568313";

    //Llave Firma Publica de Alignet
    $llaveVPOSFirmaPub = "-----BEGIN PUBLIC KEY-----\n".
    "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCvJS8zLPeePN+fbJeIvp/jjvLW\n".
    "Aedyx8UcfS1eM/a+Vv2yHTxCLy79dEIygDVE6CTKbP1eqwsxRg2Z/dI+/e14WDRs\n".
    "g0QzDdjVFIuXLKJ0zIgDw6kQd1ovbqpdTn4wnnvwUCNpBASitdjpTcNTKONfXMtH\n".
    "pIs4aIDXarTYJGWlyQIDAQAB\n".
    "-----END PUBLIC KEY-----";

    //Llave Crypto Privada del Comercio
    $llaveComercioCryptoPriv = "-----BEGIN RSA PRIVATE KEY-----\n".
    "MIICXAIBAAKBgQDwWDRcoeu6EEWlMOQuIPzRWOwHxUn9IHFMoT4VmwyEUgis83Sj\n".
    "Ba3nE6w84wyrp00MXEONmqyOBHDFaD3+fZXr8CbkU/BiliOBDCramikRVR2JZeSt\n".
    "GYHAfe7FO7hjGHgjlgOc9wsTLkKkzHKz8z5Xc6ycvaFMcPP+OS86c1S2mwIDAQAB\n".
    "AoGAWlve+8CIfkBl3rAd6VXPlulGe7lpkrfiwLuSOs87CnhI+LTi8fNNqSWVSKLX\n".
    "/aT9a5s4boFrRE1ZFG6XeBlBBmOUkq7IDzWrWzFopssT9+aThe2hsq4mAstIJqVm\n".
    "j7oOxDVyzhdBmP/9tBQ8nK8oWNDvEniu4x2UwwcGaJQrW7ECQQD44VLdi9WiQLkE\n".
    "zqUQcFB3Yj4q+75fsuUEIkuEePyrG+eGWsmnetmt6brTBlyz4KJD1cxQeTLSxA3T\n".
    "J1Ll0N6jAkEA9zhewWQFUvLjKNlQO5dUMO47H7vDiTo6f/3kJUP7FNJbKqxk3TbD\n".
    "ht1tffDd0lUthXRCsCiB4y2oRe55TFQfqQJBAPihKQZYjshzviIWSoI8obZSN+b6\n".
    "7XlvHyjdFfI2Z7yMuOPYyMF+kf4SlGgCYBP24kPAT0dJfMNfxqveCgu6eN8CQFAR\n".
    "t87XAEpvVFdkmvHR/3ihkBClFZ2aeFv/9SaEsAt8Xf6iO0DSfd1uVgoWOyHmaIny\n".
    "r66yT+8uWHPd2vd3v5ECQH0LodN1SJn4CLsNCRR+mr0y9POHrDRd+/qD+43aSNtT\n".
    "dbMbquMxhUv0AerPIRBeE7zuxUXXD9seKIRtNuqLaqI=\n".
    "-----END RSA PRIVATE KEY-----";

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

    if ($_GET['action'] == 'redirect') {
      $this->finalRedirect();
    } else {

      $result = '';

      $this->setTemplate('validation.tpl');



      $extra_vars = array();

      $cart = $this->context->cart;
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
