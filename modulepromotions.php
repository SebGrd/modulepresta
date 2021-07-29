<?php

if (!defined('_PS_VERSION_')){
    exit;
}

class ModulePromotions extends Module {
    public function __construct()
    {
        $this->name = 'module_promotions';
        $this->tab = 'front_office_features';
        $this->version = '0.1';
        $this->author = 'ESGI';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => 1.6,
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Module de promotions');
        $this->description = $this->l('Description');

        $this->confirmUninstall = $this->l('Désinstaller le module ?');
    }

    public function install() {
        if (!parent::install() ||
            !$this->registerHook('leftColumn') ||
            !$this->registerHook('header') ||
            !Configuration::updateValue('module_promotions', 'Hello')) {
            return false;
        }
        return true;
    }

    public function uninstall() {
        if (!parent::uninstall() ||
        !Configuration::deleteByName('MODULE_PROMOTIONS')) {
            return false;
        }
        return true;
    }

    public function getContent() {
        $output = null;
        if (Tools::isSubmit('btnSubmit')){
            $pageName = strval(Tools::getValue('MODULE_PROMOTIONS'));
            if (!$pageName || empty($pageName)) {
                $output .= $this->displayError($this->l('Valeur invalide'));
            } else {
                Configuration::updateValue('MODULE_PROMOTIONS', $pageName);
                $output .= $this->displayConfirmation($this->l('Valur mise à jour'));
            }
        }
        $output.= 'Test form';
        return $output.$this->displayForm();
    }

    public function displayForm() {
        $form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->l('Configuration value'),
                        'name' => 'MODULE_PROMOTIONS',
                        'size' => 20,
                        'required' => true,
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'name' => 'btnSubmit',
                ]
            ]
        ];
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex."&configure={$this->name}";
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->default_form_language = $defaultLang;
        $helper->fields_value['MODULE_PROMOTIONS'] = Configuration::get('MODULE_PROMOTIONS');
        return $helper->generateForm([$form]);
    }

    public function hookDisplayLeftColumn($params) {
        $this->context->smarty->assign([
            'module_promotions' => Configuration::get('MODULE_PROMOTIONS')
        ]);
        return $this->display(__FILE__, 'mymodule.tpl');
    }

    public function hookDisplayHeader() {
        $this->context->controller->addCSS($this->_path.'views/css/mymodule.css');
    }
}