<?php
class VPOSIntegrationPaymentModuleFrontController extends
  ModuleFrontController
{
  public $ssl = true;

  private function checkCurrency() {
    $currency_order = new Currency($this->context->cart->id_currency);
    $currencies_module = $this->module->getCurrency($this->context->cart->id_currency);

    if(is_array($currencies_module))
      foreach ($currencies_module as $currency_module)
    if ($currency_order->id == $currencies_module['id_currency'])
      return true;

    return false;
  }

  public function initContent() {
    parent::initContent();

    $this->setTemplate('payment.tpl');

    $this->display_column_left = false;
    $this->display_column_right = false;

    // if(!$this->checkCurrency())
    // Tools::redirect('index.php?controller=order');

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
    ));
  }


}
