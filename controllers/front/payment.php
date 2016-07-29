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

  public function initContent() {
    parent::initContent();

    $this->setTemplate('payment.tpl');

    //Componentes de Seguridad
    //Vector Hexadecimal
    $vector = "0000000000000000";

    //Llave Publica Crypto de Alignet. Nota olvidar ingresar los saltos de linea detallados con el valor \n
    $llaveVPOSCryptoPub = "-----BEGIN PUBLIC KEY-----\n".
    "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDTJt+hUZiShEKFfs7DShsXCkoq\n".
    "TEjv0SFkTM04qHyHFU90Da8Ep1F0gI2SFpCkLmQtsXKOrLrQTF0100dL/gDQlLt0\n".
    "Ut8kM/PRLEM5thMPqtPq6G1GTjqmcsPzUUL18+tYwN3xFi4XBog4Hdv0ml1SRkVO\n".
    "DRr1jPeilfsiFwiO8wIDAQAB\n".
    "-----END PUBLIC KEY-----";

    //Llave Firma Privada del Comercio. Nota olvidar ingresar los saltos de linea detallados con el valor \n
    $llaveComercioFirmaPriv = "-----BEGIN RSA PRIVATE KEY-----\n".
    "MIICWwIBAAKBgQC8QQR8UNPY3q7j/24RM+yOwB3qNAHxRGIcFeT681dnN4fZwc51\n".
    "K3CKN0m6ZWuOO2Zra2d1ePdm/yCDFL0A2Zn60hRDDDwUQBrP0wuQhV5QVllkGLX8\n".
    "A55rNpskJHck0hExSI40J9wO1RGyZdSIczqHAEtQByXmg/rS0mtydpeilQIDAQAV\n".
    "AoGAcfxPkvyt9Z+J+0qCf7rmvHCrYGFkB/rxjo4ZLmZA1vSiOPeUSxGNE0R0DF6V\n".
    "6LOnheXrp3Pt5mA0vtESa1b9/WjDxPkaUA9t39qLjJtzU5sbcvkbFlBfrGonr50/\n".
    "RUd+VQhGBj3gTEEyxPc0+C0bdrhvIh2QpZuSkVNbpWl+Zf0CQQDPYSjj6Qs3lGfV\n".
    "JewxN6zvlkMiRIC/2KpRCoURchlYp18zX/kNQ+DTV1WrgJpogHo6eIopkT89DzXy\n".
    "NOC63Cr9AkEA6GPzAiOzwptawiJoPy1bqmiAUApiqMEloIQKIQvBa/2zjnFk9SQH\n".
    "hUfiuS9JrXLtQPPPthqTyuXBKhmgBk/leQJAS/iPaEFJEU+mxB3fKnq6/jVmGJ61\n".
    "aWvwDyFtJYCMXkkZk+p5ODrTACNuLN9iQlwjO6lnO+OgUGmKTHm6nirU/QJAHBNG\n".
    "mUedqWXA7wJtRivJrFzdY4MXi1ZpZXCivATvSFwJVkLAqZIhq+hF6IUUDMEHtmeA\n".
    "bIjvALoUxFJeBQzdvQJAfleoHn4tQJxDasHtdf08udZEEGL7N7IAwXqiwBX6UZS8\n".
    "pO+3Xot8dhviD8KLH7B+m7AjfhGuEaCaDYBIB/8Ubw==\n".
    "-----END RSA PRIVATE KEY-----";

    //Envío de Parametros a V-POS
    $array_send['acquirerId']='99';
    $array_send['commerceId']='7627';
    $array_send['purchaseOperationNumber']='90100100105';
    $array_send['purchaseAmount']='10000';
    $array_send['purchaseCurrencyCode']='840';
    $array_send['commerceMallId']='1';
    $array_send['language']='SP';
    $array_send['billingFirstName']='Juan';
    $array_send['billingLastName']='Perez';
    $array_send['billingEMail']='test@test.com';
    $array_send['billingAddress']='Direccion ABC';
    $array_send['billingZIP']='1234567890';
    $array_send['billingCity']='San Jose';
    $array_send['billingState']='San Jose';
    $array_send['billingCountry']='CR';
    $array_send['billingPhone']='123456789';
    $array_send['shippingAddress']='Direccion ABC';
    $array_send['terminalCode']='00000000';

    //Ejemplo envío campos reservados en parametro reserved1.
    $array_send['reserved1']='Valor Reservado 123';

    //Parametros de Solicitud de Autorización a Enviar
    $array_get['XMLREQ']="";
    $array_get['DIGITALSIGN']="";
    $array_get['SESSIONKEY']="";

    //Ejecución de Creación de Valores para la Solicitud de Autorización
    VPOSSend($array_send,$array_get,$llaveVPOSCryptoPub,$llaveComercioFirmaPriv,$vector);

    // $this->display_column_left = false;
    // $this->display_column_right = false;

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
