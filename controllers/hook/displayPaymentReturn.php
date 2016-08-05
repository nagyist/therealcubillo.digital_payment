<?php
/**
 *
 */
class VPOSIntegrationDisplayPaymentReturnController
{

  public function __construct($module, $file, $path)
	{
		$this->file = $file;
		$this->module = $module;
		$this->context = Context::getContext();
		$this->_path = $path;
	}

	public function run($params)
	{
		if ($params['objOrder']->payment != $this->module->displayName)
			return '';
		$reference = $params['objOrder']->id;
		if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
			$reference = $params['objOrder']->reference;
		$total_to_pay = Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false);
    $vpos_result = $_GET['result'];
		$this->context->smarty->assign(array(
			'reference' => $reference,
			'total_to_pay' => $total_to_pay,
      'result' => $vpos_result
		));

		return $this->module->display($this->file, 'displayPaymentReturn.tpl');
	}
}
