<?php
  if (!defined('_PS_VERSION_'))
   exit;

  class VPOSIntegration extends Module
  {
    public function __construct()
    {
       $this->name = 'vposintegration';
       $this->tab = 'payments_gateways';
       $this->version = '1.0.0';
       $this->author = 'Luis Cubillo';
       $this->need_instance = 1;
       $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
       $this->bootstrap = true;

       parent::__construct();

       $this->displayName = $this->l('Integracion con VPOS');
       $this->description = $this->l('Modulo para pago electronico utilizando VPOS');

       $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

       if (!Configuration::get('VPOSINTEGRATION_NAME'))
         $this->warning = $this->l('No name provided');
     }

     public function install()
     {
        if (!parent::install() ||
          !$this->registerHook('leftColumn') ||
          !$this->registerHook('header') ||
          !Configuration::updateValue('ENVIRONMENT', '1') ||
          !Configuration::updateValue('ID_ACQUIRER', '99') ||
          !Configuration::updateValue('ID_COMMERCE', '7761') ||
          !Configuration::updateValue('TERMINAL_CODE', '00000000') ||
          !Configuration::updateValue('ID_MALL', '1') ||
          !Configuration::updateValue('VECTOR', '0000000000000000') ||
          !Configuration::updateValue('SIGN_PRIV_KEY', "-----BEGIN RSA PRIVATE KEY-----\n".
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
          "-----END RSA PRIVATE KEY-----") ||
          !Configuration::updateValue('SIGN_PUB_KEY', "-----BEGIN PUBLIC KEY-----\n".
          "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCvJS8zLPeePN+fbJeIvp/jjvLW\n".
          "Aedyx8UcfS1eM/a+Vv2yHTxCLy79dEIygDVE6CTKbP1eqwsxRg2Z/dI+/e14WDRs\n".
          "g0QzDdjVFIuXLKJ0zIgDw6kQd1ovbqpdTn4wnnvwUCNpBASitdjpTcNTKONfXMtH\n".
          "pIs4aIDXarTYJGWlyQIDAQAB\n".
          "-----END PUBLIC KEY-----") ||
          !Configuration::updateValue('ENC_PRIV_KEY', "-----BEGIN RSA PRIVATE KEY-----\n".
          "MIICXgIBAAKBgQCawGf6/Vwsd0Msnd4JbJTKQ3IAkIOvNimD4t21VBqaBIz+NL5X\n".
          "nSzHPRSIuLTHjnphTcLLDSja2fU8HciQD9/dg1UQpW08MGN8DbmhFsAu6LOQrTku\n".
          "ixW3fjmXHU8xjtezRx5JFXIXBiUUxNQthl5N7nTUSCh+5R6DoQ1kCieQcQIDAQAV\n".
          "AoGAdFrJAjYeQeou7RYtbOaeoXkpLvHXZD2If0JSBaYR/nsqHME5i1cFyUR68prd\n".
          "K66g0jvoZv2r+jbFxwSnNjqts0Q6KXYXNEO43D31TMd2UpnFM08yfKRobHq1ZvVy\n".
          "o33gxbAD6iizE4vubCVXXkDAl7/JjSLNB0yZxnrXyvk9O80CQQDSHGNxSy2z+HgI\n".
          "y9I/HSpgpryn3+LPLgJBfHzsZUyt9LMyZocdVcLolEhZDNBM6mKNxSMinDEL42a+\n".
          "hO1ch/Q1AkEAvIzL/oUD9Afd7HmrDd2DG6+G60rZJgRXHWcp9E9nhz/DbFKH8j1b\n".
          "4znVG99N/9RuHGTBLBu13v2IAiW/6ME6zQJBAJWnn0IaKkywD+TGpGMnv3zjzd6g\n".
          "y0L8wzNxYBzz1N/C6+fg83S4FaM7sp57lw5JG3TODp9nwZcdAkUyvIzj9xUCQQCv\n".
          "/ipytrtWkOqDmkJeLXS620zFDyWIWegSy5NKJrqlyaCmS17+FBDRRQngCdwd6t1X\n".
          "XKx/T6IE3WS/dZiNCDGBAkEAgZmJkO6XqoK+bueXH7OvyP+SeZtQ4zR5pd0fYW6W\n".
          "jPOu9ZbZ8cB7Pu1gPHvpsDP5t1zBrlnGOWfRmvi17WijhA==\n".
          "-----END RSA PRIVATE KEY-----") ||
          !Configuration::updateValue('ENC_PUB_KEY', "-----BEGIN PUBLIC KEY-----\n".
          "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDTJt+hUZiShEKFfs7DShsXCkoq\n".
          "TEjv0SFkTM04qHyHFU90Da8Ep1F0gI2SFpCkLmQtsXKOrLrQTF0100dL/gDQlLt0\n".
          "Ut8kM/PRLEM5thMPqtPq6G1GTjqmcsPzUUL18+tYwN3xFi4XBog4Hdv0ml1SRkVO\n".
          "DRr1jPeilfsiFwiO8wIDAQAB\n".
          "-----END PUBLIC KEY-----")
        )
          return false;
        return true;
    }

    public function uninstall()
    {
      if (!parent::uninstall() ||
          !Configuration::deleteByName('ENVIRONMENT') ||
          !Configuration::deleteByName('ID_ACQUIRER') ||
          !Configuration::deleteByName('ID_ACQUIRER') ||
          !Configuration::deleteByName('ID_COMMERCE') ||
          !Configuration::deleteByName('TERMINAL_CODE') ||
          !Configuration::deleteByName('ID_MALL') ||
          !Configuration::deleteByName('VECTOR') ||
          !Configuration::deleteByName('SIGN_PRIV_KEY') ||
          !Configuration::deleteByName('SIGN_PUB_KEY') ||
          !Configuration::deleteByName('ENC_PRIV_KEY') ||
          !Configuration::deleteByName('ENC_PUB_KEY')
      )
        return false;
      return true;
    }


    public function getContent()
    {


        if (Tools::isSubmit('vpos_pago'))
        {

        }

        return $this->display(__FILE__, 'getContent.tpl');
    }

    public function displayForm()
    {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $environment_options = array(
          array(
            'id_option' => 1,                 // The value of the 'value' attribute of the <option> tag.
            'name' => 'Debug'              // The value of the text content of the  <option> tag.
          ),
          array(
            'id_option' => 2,
            'name' => 'Produccion'
          ),
        );

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Ajustes'),
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Ambiente'),
                    'selected' => 'ENVIRONMENT',
                    'name' => 'ENVIRONMENT',
                    'options' => array(
                      'query' => $environment_options,                           // $options contains the data itself.
                      'id' => 'id_option',                           // The value of the 'id' key must be the same as the key for 'value' attribute of the <option> tag in each $options sub-array.
                      'name' => 'name'                               // The value of the 'name' key must be the same as the key for the text content of the <option> tag in each $options sub-array.
                    ),
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('ID Adquiriente'),
                    'name' => 'ID_ACQUIRER',
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('ID Comercio'),
                    'name' => 'ID_COMMERCE',
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Code Terminal'),
                    'name' => 'TERMINAL_CODE',
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('ID Mall'),
                    'name' => 'ID_MALL',
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Vector de inicializacion'),
                    'name' => 'VECTOR',
                    'size' => 16,
                    'required' => true
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Llave Privada Firma'),
                    'name' => 'SIGN_PRIV_KEY',
                    'required' => true
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Llave Publica Firma'),
                    'name' => 'SIGN_PUB_KEY',
                    'required' => true
                )
                ,
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Llave Privada Encriptacion'),
                    'name' => 'ENC_PRIV_KEY',
                    'required' => true
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Llave Publica Encriptacion'),
                    'name' => 'ENC_PUB_KEY',
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['ENVIRONMENT'] = Configuration::get('ENVIRONMENT');
        $helper->fields_value['ID_ACQUIRER'] = Configuration::get('ID_ACQUIRER');
        $helper->fields_value['ID_COMMERCE'] = Configuration::get('ID_COMMERCE');
        $helper->fields_value['TERMINAL_CODE'] = Configuration::get('TERMINAL_CODE');
        $helper->fields_value['ID_MALL'] = Configuration::get('ID_MALL');
        $helper->fields_value['VECTOR'] = Configuration::get('VECTOR');
        $helper->fields_value['SIGN_PRIV_KEY'] = Configuration::get('SIGN_PRIV_KEY');
        $helper->fields_value['SIGN_PUB_KEY'] = Configuration::get('SIGN_PUB_KEY');
        $helper->fields_value['ENC_PRIV_KEY'] = Configuration::get('ENC_PRIV_KEY');
        $helper->fields_value['ENC_PUB_KEY'] = Configuration::get('ENC_PUB_KEY');

        return $helper->generateForm($fields_form);
    }

    public function hookDisplayLeftColumn($params)
    {
      $this->context->smarty->assign(
          array(
              'my_module_name' => Configuration::get('VPOSINTEGRATION_NAME'),
              'my_module_link' => $this->context->link->getModuleLink('vposintegration', 'display')
          )
      );
      return $this->display(__FILE__, 'vposintegration.tpl');
    }

    public function hookDisplayRightColumn($params)
    {
      return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayHeader()
    {
      $this->context->controller->addCSS($this->_path.'css/mymodule.css', 'all');
    }
  }
 ?>
