<?php

if (!defined('_PS_VERSION_')){
    exit;
}

class ModulePromo extends Module {
    public function __construct()
    {
        $this->name = 'modulepromo';
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
            !$this->registerHook('home') ||
            !$this->registerHook('header') ||
            !Configuration::updateValue('modulepromo', 'Hello')) {
            return false;
        }
        return true;
    }

    public function uninstall() {
        if (!parent::uninstall() ||
        !Configuration::deleteByName('MODULEPROMO')) {
            return false;
        }
        return true;
    }

    public function getContent() {
        $output = null;
        if (Tools::isSubmit('btnSubmit')){
            $pageName = strval(Tools::getValue('MODULEPROMO'));
            if (!$pageName || empty($pageName)) {
                $output .= $this->displayError($this->l('Valeur invalide'));
            } else {
                Configuration::updateValue('MODULEPROMO', $pageName);
                $output .= $this->displayConfirmation($this->l('Valeur mise à jour'));
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
                        'name' => 'MODULEPROMO',
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
        $helper->fields_value['MODULEPROMO'] = Configuration::get('MODULEPROMO');
        return $helper->generateForm([$form]);
    }

    public function DisplayHome($params) {
        $this->context->smarty->assign([
            'modulepromo' => Configuration::get('MODULEPROMO')
        ]);
        return $this->display(__FILE__, 'mymodule.tpl');
    }

    public function hookDisplayHeader() {
        $this->context->controller->addCSS($this->_path.'views/css/mymodule.css');
    }
}