<?php
include("vpos_plugin.php");
class VPOSIntegrationPaymentModuleFrontController extends
  ModuleFrontController
{
  public $ssl = true;
  public $display_column_left = false;

  private function checkCurrency() {
    $currency_order = new Currency($this->context->cart->id_currency);
    $currencies_module = $this->module->getCurrency($this->context->cart->id_currency);

    if(is_array($currencies_module))
      foreach ($currencies_module as $currency_module)
    if ($currency_order->id == $currency_module['id_currency'])
      return true;

    return false;
  }

  private function requestVPOSPlugin() {
    //Componentes de Seguridad
    //Vector Hexadecimal
    $vector = Configuration::get('VPOSI_VECTOR');

    //Llave Publica Crypto de Alignet. Nota olvidar ingresar los saltos de linea detallados con el valor \n
    $llaveVPOSCryptoPub = Configuration::get('VPOSI_ENC_PUB_KEY');

    //Llave Firma Privada del Comercio. Nota olvidar ingresar los saltos de linea detallados con el valor \n
    $llaveComercioFirmaPriv = Configuration::get('VPOSI_SIGN_PRIV_KEY');

    $address = new Address($this->context->cart->id_address_delivery);

    $state = new State($address->id_state);

    //Envío de Parametros a V-POS
    $array_send['acquirerId'] = Configuration::get('VPOSI_ID_ACQUIRER');
    $array_send['commerceId']= Configuration::get('VPOSI_ID_COMMERCE');
    $array_send['purchaseOperationNumber']= $this->context->cart->id;
    $array_send['purchaseAmount'] = $this->context->cart->getOrderTotal(true, Cart::BOTH);
    $array_send['purchaseCurrencyCode'] = $this->context->cart->id_currency;
    $array_send['commerceMallId'] = Configuration::get('VPOSI_ID_MALL');
    $array_send['language'] = 'SP';
    $array_send['billingFirstName'] = $address->firstname;
    $array_send['billingLastName'] = $address->lastname;;
    $array_send['billingEMail'] = $this->context->customer->email;
    $array_send['billingAddress'] = $address->address1.', '.$address->address2;
    $array_send['billingZIP'] = $address->postcode;
    $array_send['billingCity'] = $address->city;
    $array_send['billingState'] = $state->name;
    $array_send['billingCountry'] = $address->country;
    $array_send['billingPhone'] = $address->phone;
    $array_send['shippingAddress'] = $address->address1.', '.$address->address2;
    $array_send['terminalCode'] = Configuration::get('VPOSI_TERMINAL_CODE');

    //Ejemplo envío campos reservados en parametro reserved1.
    // $array_send['reserved1']='Valor Reservado 123';

    //Parametros de Solicitud de Autorización a Enviar
    $array_get['XMLREQ'] = "";
    $array_get['DIGITALSIGN'] = "";
    $array_get['SESSIONKEY'] = "";

    //Ejecución de Creación de Valores para la Solicitud de Autorización
    VPOSSend($array_send,$array_get,$llaveVPOSCryptoPub,$llaveComercioFirmaPriv,$vector);

    return $array_get;
  }

  public function initContent() {
    parent::initContent();

    $this->setTemplate('payment.tpl');

    $array_get = $this->requestVPOSPlugin();

    $this->display_column_left = false;
    $this->display_column_right = false;

    if(!$this->checkCurrency())
    Tools::redirect('index.php?controller=order');

    if (count($this->context->cart->getProducts()) > 1) {
      foreach ($this->context->cart->getProducts() as $product) {
        $name = $product->$name;
       print_r($name);
      }
    } else {
      $product = $this->context->cart->getProducts();
      print_r($product->Sname);
    }

    #Cart info
    $this->context->smarty->assign(array(
      'nb_products' => $this->context->cart->nbProducts(),
      'products' => $this->context->cart->getProducts(),
      'cart_currency' => $this->context->cart->id_currency,
      'currencies' => $this->module->getCurrency((int)$this->context->cart->id_currency),
      'total_amount' => $this->context->cart->getOrderTotal(true, Cart::BOTH),
      'path' => $this->module->getPathUri(),
      'id_adquirer' => $array_send['acquirerId'],
      'id_commerce' => $array_send['commerceId'],
      'xmlreq' => $array_get['XMLREQ'],
      'digitalsign' => $array_get['DIGITALSIGN'],
      'sessionkey' => $array_get['SESSIONKEY'],
    ));
  }


}
