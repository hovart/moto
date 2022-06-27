<?php
/**
 * Main class used by the module export_catalog
 *
 * @category  Prestashop
 * @category  Module
 * @author    Samdha <contact@samdha.net>
 * @copyright Samdha
 * @license   commercial license see license.txt
 */

class Samdha_ExportCatalog_Main extends Samdha_Commons_Module
{
    public $config_global = true;
    public $short_name = 'exp_cat';
    public $mail_path = null;
    public $id_addons = 5477;

    public function __construct()
    {
        $this->name = 'export_catalog';
        if (version_compare(_PS_VERSION_, '1.4.0.0', '<')) {
            $this->tab = 'Tools';
        } else {
            $this->tab = 'export';
        }
        $this->version = '2.44.0';
        $this->module_key = '2273c3ab9415bd47e68a79cc0efe273e';

        /* Validator compatibility *//*
        $this->author = 'Samdha';
        if (version_compare(_PS_VERSION_, '1.5.0.0', '<'))
            require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');
        if (function_exist(function_exists('curl_init'))
            require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');
        */
        parent::__construct();

        $this->author = 'Samdha';
        $this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Catalog in CSV format', 'main');
        $this->description = $this->l('Allows you to download your catalog in CSV format.', 'main');
        $this->mail_path = _PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'mails'.DIRECTORY_SEPARATOR;

        $this->tools = new Samdha_ExportCatalog_Tools($this);

        if (function_exists('curl_init') == false) {
            $this->warning = null; // for validator
        }
    }

    public function postProcess($token)
    {
        $currentIndex = AdminController::$currentIndex;

        // need to convert the old config ?
        if (is_writable(_PS_ROOT_DIR_.$this->config->_directory)
            && $this->config->_filename) {
            $this->tools->importOldConfig();
            $this->postUpdateModule();
            $url = $currentIndex
                .'&module_name='.$this->name
                .'&configure='.$this->name
                .'&token='.$token;
            Tools::redirectAdmin($url);
        }

        if (Tools::isSubmit('actionSaveModel')) {
            $conf = new Samdha_ExportCatalog_Model(
                _PS_ROOT_DIR_.$this->config->_directory,
                $this,
                Tools::getValue($this->short_name.'_model')
            );

            // if no categorie is checked
            $model = Tools::getValue('model');
            if (!is_array($model)) { // should never happend
                $_POST['model'] = array();
                $_POST['model']['datas'] = array();
                $_POST['model']['datas']['categories'] = array();
                $_POST['model']['datas']['manufacturers'] = array();
                $_POST['model']['datas']['suppliers'] = array();
            } elseif (!isset($model['datas']) || !is_array($model['datas'])) { // should never happend
                $_POST['model']['datas'] = array();
                $_POST['model']['datas']['categories'] = array();
                $_POST['model']['datas']['manufacturers'] = array();
                $_POST['model']['datas']['suppliers'] = array();
            }

            // may happend (but should not)
            if (!isset($model['datas']['categories']) || !is_array($model['datas']['categories'])) {
                $_POST['model']['datas']['categories'] = array();
            }
            if (!isset($model['datas']['manufacturers']) || !is_array($model['datas']['manufacturers'])) {
                $_POST['model']['datas']['manufacturers'] = array();
            }
            if (!isset($model['datas']['suppliers']) || !is_array($model['datas']['suppliers'])) {
                $_POST['model']['datas']['suppliers'] = array();
            }

            $conf->loadFromArray($this->samdha_tools->stripSlashesArray(Tools::getValue('model')));
            $conf->save();
            $this->config->_model = $conf->filename;
            $url = $currentIndex
                .'&module_name='.$this->name
                .'&configure='.$this->name
                .'&conf='.(Tools::getValue($this->short_name.'_model')?'4':'3')
                .'&token='.$token;
            Tools::redirectAdmin($url);
        }

        if (Tools::isSubmit('actionDuplicateModel')) {
            $conf = new Samdha_ExportCatalog_Model(
                _PS_ROOT_DIR_.$this->config->_directory,
                $this,
                Tools::getValue($this->short_name.'_model')
            );
            $conf->filename = '';
            $conf->name .= ' '.$this->l('(copy)', 'main');
            $conf->save();
            $this->config->_model = $conf->filename;
            $url = $currentIndex
                .'&module_name='.$this->name
                .'&configure='.$this->name
                .'&conf=19&token='.$token;
            Tools::redirectAdmin($url);
        }

        if (Tools::isSubmit('actionDeleteModel')) {
            $conf = new Samdha_ExportCatalog_Model(
                _PS_ROOT_DIR_.$this->config->_directory,
                $this,
                Tools::getValue($this->short_name.'_model')
            );
            $conf->delete();
            $files = $conf->getFiles();
            if (!empty($files)) {
                $keys = array_keys($files);
                $this->config->_model = reset($keys);
            } else {
                $this->config->_model = '';
            }
            $url = $currentIndex
                .'&module_name='.$this->name
                .'&configure='.$this->name
                .'&conf=1&token='.$token;
            Tools::redirectAdmin($url);
        }

        if (Tools::isSubmit('actionLoadModel')) {
            $conf = new Samdha_ExportCatalog_Model(
                _PS_ROOT_DIR_.$this->config->_directory,
                $this,
                Tools::getValue($this->short_name.'_model')
            );
            $this->config->_model = $conf->filename;
            $url = $currentIndex
                .'&module_name='.$this->name
                .'&configure='.$this->name
                .'&token='.$token;
            Tools::redirectAdmin($url);
        }

        if (Tools::isSubmit('actionDownloadModel')) {
            if (ob_get_level()) {
                ob_end_clean();
            }
            ob_start();
            if (version_compare(_PS_VERSION_, '1.5.3.0', '>=')) {
                register_shutdown_function(array($this->tools, 'fixDisplayFatalError'));
            }
            $conf = new Samdha_ExportCatalog_Model(
                _PS_ROOT_DIR_.$this->config->_directory,
                $this,
                Tools::getValue($this->short_name.'_model')
            );
            $conf->sendToBrowser();
            $url = $currentIndex
                .'&module_name='.$this->name
                .'&configure='.$this->name
                .'&token='.$token;
            Tools::redirectAdmin($url);
        }

        if (Tools::isSubmit('actionUploadModel')) {
            if ($_FILES['model_filename']['tmp_name']) {
                $conf = new Samdha_ExportCatalog_Model(
                    _PS_ROOT_DIR_.$this->config->_directory,
                    $this
                );
                if ($conf->copyFromFile($_FILES['model_filename']['tmp_name'])) {
                    $this->config->_model = $conf->filename;
                    $url = $currentIndex
                        .'&module_name='.$this->name
                        .'&configure='.$this->name
                        .'&conf=18&token='.$token;
                    Tools::redirectAdmin($url);
                } else {
                    $this->errors[] = $this->l('Upload error.', 'main');
                }
            } else {
                $this->errors[] = $this->l('Upload error.', 'main');
            }
        }

        if (Tools::isSubmit('actionSaveExport')) {
            $export = Tools::getValue('export');
            if (!isset($export['datas']['days']) || !is_array($export['datas']['days'])) {
                $_POST['export']['datas']['days'] = array();
            }
            if (!isset($export['datas']['employees']) || !is_array($export['datas']['employees'])) {
                $_POST['export']['datas']['employees'] = array();
            }
            if (!isset($export['datas']['hours']) || !is_array($export['datas']['hours'])) {
                $_POST['export']['datas']['hours'] = array();
            }
            if (!isset($export['datas']['minutes']) || !is_array($export['datas']['minutes'])) {
                $_POST['export']['datas']['minutes'] = array(0);
            }

            $conf = new Samdha_ExportCatalog_Export(
                _PS_ROOT_DIR_.$this->config->_directory,
                $this,
                Tools::getValue($this->short_name.'_export')
            );
            $conf->loadFromArray($this->samdha_tools->stripSlashesArray(Tools::getValue('export')));
            $conf->save();
            $this->config->_export = $conf->filename;
            $url = $currentIndex
                .'&module_name='.$this->name
                .'&configure='.$this->name
                .'&conf='.(Tools::getValue($this->short_name.'_export')?'4':'3')
                .'&token='.$token
                .'&active_tab=tabCron';
            Tools::redirectAdmin($url);
        }

        if (Tools::isSubmit('actionDuplicateExport')) {
            $conf = new Samdha_ExportCatalog_Export(
                _PS_ROOT_DIR_.$this->config->_directory,
                $this,
                Tools::getValue($this->short_name.'_export')
            );
            $conf->filename = '';
            $conf->name .= ' '.$this->l('(copy)', 'main');
            $conf->save();
            $this->config->_export = $conf->filename;
            $url = $currentIndex
                .'&module_name='.$this->name
                .'&configure='.$this->name
                .'&conf=19&token='.$token
                .'&active_tab=tabCron';
            Tools::redirectAdmin($url);
        }

        if (Tools::isSubmit('actionDeleteExport')) {
            $conf = new Samdha_ExportCatalog_Export(
                _PS_ROOT_DIR_.$this->config->_directory,
                $this,
                Tools::getValue($this->short_name.'_export')
            );
            $conf->delete();
            $files = $conf->getFiles();
            if (!empty($files)) {
                $keys = array_keys($files);
                $this->config->_export = reset($keys);
            } else {
                $this->config->_export = '';
            }
            $url = $currentIndex
                .'&module_name='.$this->name
                .'&configure='.$this->name
                .'&conf=1&token='.$token
                .'&active_tab=tabCron';
            Tools::redirectAdmin($url);
        }

        if (Tools::isSubmit('actionLoadExport')) {
            $conf = new Samdha_ExportCatalog_Export(
                _PS_ROOT_DIR_.$this->config->_directory,
                $this,
                Tools::getValue($this->short_name.'_export')
            );
            $this->config->_export = $conf->filename;
            $url = $currentIndex
                .'&module_name='.$this->name
                .'&configure='.$this->name
                .'&token='.$token
                .'&active_tab=tabCron';
            Tools::redirectAdmin($url);
        }

        if (Tools::isSubmit('actionExportModel')) {
            if (ob_get_level()) {
                ob_end_clean();
            }
            ob_start();
            if (version_compare(_PS_VERSION_, '1.5.3.0', '>=')) {
                register_shutdown_function(array($this->tools, 'fixDisplayFatalError'));
            }
            $model = new Samdha_ExportCatalog_Model(
                _PS_ROOT_DIR_.$this->config->_directory,
                $this,
                Tools::getValue('model')
            );
            $model->restoreContext();
            $model->export($this->tools->decode(Tools::getValue('filename')));
            die();
        }

        if (Tools::getValue('ajax')
            && ($action = Tools::getValue('action'))
            && method_exists($this, $action)) {
            if (ob_get_level()) {
                ob_end_clean();
            }
            ob_start();
            if (version_compare(_PS_VERSION_, '1.5.3.0', '>=')) {
                register_shutdown_function(array($this->tools, 'fixDisplayFatalError'));
            }
            call_user_func(array($this, $action));
            die();
        }

        $result = parent::postProcess($token);

        if (!is_writable(_PS_ROOT_DIR_.$this->config->_directory)) {
            $message = $this->l('The working directory is not writable.', 'main');
            $message .= ' '.$this->l('Make it writable or choose another directory in the "Parameters" tab.', 'main');
            $this->errors[] = $message;
        }

        if ($this->config->_export) {
            $iso_lang = Language::getIsoById((int)Configuration::get('PS_LANG_DEFAULT'));
            if (!file_exists($this->mail_path.$iso_lang.DIRECTORY_SEPARATOR.$this->name.'.html')) {
                $message = $this->l('The mail templates are not avaible in the shop default language.', 'main');
                $message .= ' '.$this->l('You can create them using the translation tool of Prestashop.', 'main');
                $this->errors[] = $message;
            }

            if (!$this->samdha_tools->canAccessInternet()) {
                $message = $this->l('FTP export is not available on this server.', 'main');
                $message .= ' '.$this->l('If you need it enable curl or allow_url_fopen.', 'main');
                $this->warnings[] = $message;
            }

            // reinstall cron if deleted
            $cron_module = Module::getInstanceByName('cron');
            $cron_exists = Validate::isLoadedObject($cron_module) && $cron_module->active;

            if ($this->config->_cron
                && $cron_exists
                && !$cron_module->cronExists($this->id, 'cron')) {
                $cron_module->addCron($this->id, 'cron', '0,10,20,30,40,50 * * * *');
            }

            if ($this->config->_cron
                && $cron_exists
                && !$cron_module->cronExists($this->id, 'cron')) {
                $this->errors = array_merge($this->errors, $cron_module->_postErrors);
                $this->errors[] = $this->l('Unabled to create cron task', 'main');
            }

            if (!$this->config->_cron
                && $cron_exists
                && $cron_module->cronExists($this->id, 'cron')) {
                $cron_module->deleteCron($this->id, 'cron');
            }

            if (!isset($this->config->_token) || !$this->config->_token) {
                $this->config->_token = Tools::passwdGen();
            }
        } else {
            $cron_module = Module::getInstanceByName('cron');
            $cron_exists = Validate::isLoadedObject($cron_module) && $cron_module->active;
            if ($cron_exists && $cron_module->cronExists($this->id, 'cron')) {
                $cron_module->deleteCron($this->id, 'cron');
            }
        }
        if ((!isset($this->config->host) || !$this->config->host) && isset($_SERVER['HTTP_HOST'])) {
            $this->config->host = $this->samdha_tools->getHttpHost(true);
        }

        if ((int)$this->config->_id_customer) {
            $customer = new Customer((int)$this->config->_id_customer);
            if (!Validate::isLoadedObject($customer)) {
                $this->config->_id_customer = 0;
            } elseif (!Validate::isMD5($customer->passwd)) { // bug in version < 2.0.22.0
                            $customer->passwd = MD5($customer->passwd);
                $customer->save();
            }
        }

        return $result;
    }

    public function displayForm($token)
    {
        $cookie = $this->context->cookie;
        $smarty = $this->smarty;

        $model = new Samdha_ExportCatalog_Model(
            _PS_ROOT_DIR_.$this->config->_directory,
            $this,
            $this->config->_model
        );
        if (!$model->filename) {
            $files = $model->getFiles();
            if (!empty($files)) {
                $keys = array_keys($files);
                $model->loadFromFile(reset($keys));
                $this->config->_model = $model->filename;
            } else {
                $this->config->_model = '';
            }
        }

        $tmp_model = new Samdha_ExportCatalog_Model(_PS_ROOT_DIR_.$this->config->_directory, $this);
        $files = $tmp_model->getFiles();
        $keys = array_keys($files);
        $models_filename = array();
        $models_shop = array();
        $models_url = array();
        $models_lang = array();
        $models_currency = array();
        $version_15 = version_compare(_PS_VERSION_, '1.5.0.0', '>=');
        foreach ($keys as $key) {
            $tmp_model->loadFromFile($key);
            $filename = $tmp_model->filename;
            $models_filename[$filename] = $tmp_model->datas['filename'];
            $models_shop[$filename] = $tmp_model->datas['id_shop'];
            $models_lang[$filename] = $tmp_model->datas['id_lang'];
            $models_currency[$filename] = $tmp_model->datas['id_currency'];
            if ($version_15) {
                $url = $this->context->link->getModuleLink(
                    $this->name,
                    'export',
                    array(),
                    null,
                    $tmp_model->datas['id_lang'],
                    $tmp_model->datas['id_shop']
                );
                $models_url[$filename] = str_replace(array('http:', 'https:'), '', $url);
            } else {
                $models_url[$filename] = '//'.$this->samdha_tools->getHttpHost(false).$this->_path.'export.php';
            }
        }
        unset($tmp_model);

        $export = new Samdha_ExportCatalog_Export(
            _PS_ROOT_DIR_.$this->config->_directory,
            $this,
            $this->config->_export
        );
        if (!$export->filename) {
            $files = $export->getFiles();
            if (!empty($files)) {
                $keys = array_keys($files);
                $export->loadFromFile(reset($keys));
                $this->config->_export = $export->filename;
            } else {
                $this->config->_export = '';
            }
        }

        $tmp_export = new Samdha_ExportCatalog_Export(_PS_ROOT_DIR_.$this->config->_directory, $this);
        $files = $tmp_export->getFiles();
        $keys = array_keys($files);
        $exports_model = array();
        foreach ($keys as $key) {
            $tmp_export->loadFromFile($key);
            $exports_model[$tmp_export->filename] = $tmp_export->datas['model'];
        }
        unset($tmp_export);

        $id_lang = $cookie->id_lang;
        $tmp = Language::getLanguages();
        $languages = array();
        foreach ($tmp as $lang) {
            $languages[$lang['id_lang']] = $lang['name'];
        }

        $tmp = Currency::getCurrencies();
        $currencies = array();
        foreach ($tmp as $currency) {
            $currencies[$currency['id_currency']] = $currency['name'];
        }

        $tmp = Country::getCountries($cookie->id_lang, true);
        $countries = array();
        foreach ($tmp as $country) {
            $countries[$country['id_country']] = $country['name'];
        }

        $tmp = Group::getGroups($cookie->id_lang);
        $groups = array();
        foreach ($tmp as $group) {
            $groups[$group['id_group']] = $group['name'];
        }

        $tmp = Manufacturer::getManufacturers(false, $cookie->id_lang);
        $manufacturers = array();
        foreach ($tmp as $manufacturer) {
            $manufacturers[$manufacturer['id_manufacturer']] = $manufacturer['name'];
        }

        $tmp = Supplier::getSuppliers(false, $cookie->id_lang);
        $suppliers = array();
        foreach ($tmp as $supplier) {
            $suppliers[$supplier['id_supplier']] = $supplier['name'];
        }

        if (class_exists('Shop', false)) {
            $tmp = Shop::getShops();
            $shops = array();
            foreach ($tmp as $shop) {
                $shops[$shop['id_shop']] = $shop['name'];
            }
        } else {
            $shops = false;
        }

        $categories = $this->tools->getCategories($cookie->id_lang, false);
        $id_root_category = 1;
        $category = reset($categories);
        if (!isset($category[$id_root_category])) {
            $tmp = array_keys($category);
            $id_root_category = reset($tmp);
        }

        $tmp = ImageType::getImagesTypes('products');
        $images_types = array(0 => $this->l('Original', 'main'));
        foreach ($tmp as $image_type) {
            $images_types[$image_type['name']] = $image_type['name'];
        }

        if (function_exists('mb_list_encodings')) {
            $tmp = mb_list_encodings();
        } else {
            $tmp = array(
                'pass',
                'auto',
                'wchar',
                'byte2be',
                'byte2le',
                'byte4be',
                'byte4le',
                'BASE64',
                'UUENCODE',
                'HTML-ENTITIES',
                'Quoted-Printable',
                '7bit',
                '8bit',
                'UCS-4',
                'UCS-4BE',
                'UCS-4LE',
                'UCS-2',
                'UCS-2BE',
                'UCS-2LE',
                'UTF-32',
                'UTF-32BE',
                'UTF-32LE',
                'UTF-16',
                'UTF-16BE',
                'UTF-16LE',
                'UTF-8',
                'UTF-7',
                'UTF7-IMAP',
                'ASCII',
                'EUC-JP',
                'SJIS',
                'eucJP-win',
                'SJIS-win',
                'JIS',
                'ISO-2022-JP',
                'Windows-1252',
                'ISO-8859-1',
                'ISO-8859-2',
                'ISO-8859-3',
                'ISO-8859-4',
                'ISO-8859-5',
                'ISO-8859-6',
                'ISO-8859-7',
                'ISO-8859-8',
                'ISO-8859-9',
                'ISO-8859-10',
                'ISO-8859-13',
                'ISO-8859-14',
                'ISO-8859-15',
                'EUC-CN',
                'CP936',
                'HZ',
                'EUC-TW',
                'BIG-5',
                'EUC-KR',
                'UHC',
                'ISO-2022-KR',
                'Windows-1251',
                'CP866',
                'KOI8-R',
            );
        }
        $charsets = array();
        foreach ($tmp as $charset) {
            $charsets[$charset] = $charset;
        }

        $tmp = Employee::getEmployees();
        $employees = array();
        foreach ($tmp as $employee) {
            if (array_key_exists('firstname', $employee)) {
                $employees[$employee['id_employee']] = $employee['firstname'].' '.$employee['lastname'];
            } else {
                $employees[$employee['id_employee']] = $employee['name'];
            }
        }

        $days = array(
            0 => $this->l('Sunday', 'main'),
            1 => $this->l('Monday', 'main'),
            2 => $this->l('Tuesday', 'main'),
            3 => $this->l('Wednesday', 'main'),
            4 => $this->l('Thursday', 'main'),
            5 => $this->l('Friday', 'main'),
            6 => $this->l('Saturday', 'main')
        );

        $cron_module = Module::getInstanceByName('cron');
        $cron_exists = Validate::isLoadedObject($cron_module) && $cron_module->active;
        $task_created = $cron_exists && $cron_module->cronExists($this->id, 'cron');

        $writable = is_writable(_PS_ROOT_DIR_.$this->config->_directory);
        $tabs = array();
        if ($writable) {
            $tabs[] = array('href' => '#tabModel', 'display_name' => $this->l('Export models', 'main'));
        }
        if ($writable && $model->filename) {
            $tabs[] = array('href' => '#tabCron', 'display_name' => $this->l('Scheduled exports', 'main'));
        }
        $tabs[] = array('href' => '#tabParameters', 'display_name' => $this->l('Parameters', 'main'));

        $smarty->assign(array(
            'tabs'            => $tabs,
            'model'           => $model,
            'possible_fields' => $model->getPossibleFields($id_lang),
            'charsets'        => $charsets,
            'images_types'    => $images_types,
            'categories_html' => $this->tools->recurseCategoryForInclude(
                $model->datas['categories'],
                $categories,
                $categories[0][$id_root_category],
                $id_root_category
            ),
            'languages'       => $languages,
            'currencies'      => $currencies,
            'countries'       => $countries,
            'groups'          => $groups,
            'manufacturers'   => $manufacturers,
            'suppliers'       => $suppliers,
            'shops'           => $shops,
            'models_filename' => $models_filename,
            'models_shop'     => $models_shop,
            'models_url'      => $models_url,
            'models_lang'     => $models_lang,
            'models_currency' => $models_currency,
            'exports_model'   => $exports_model,

            'export'          => $export,
            'employees'       => $employees,
            'days'            => $days,
            'hours'           => range(0, 23),
            'minutes'         => range(0, 59),

            'cron_exists'     => $cron_exists,
            'task_created'    => $task_created,
            'php_dir'         => $this->tools->getPHPExecutableFromPath(),

            'writable'        => $writable,
            'shop_url'        => $this->samdha_tools->getHttpHost(true).__PS_BASE_URI__,
            'internet_access' => $this->samdha_tools->canAccessInternet(),
        ));
        // Display Form
        return parent::displayForm($token);
    }

    /* set default config */
    public function getDefaultConfig()
    {
        return array(
            '_cron' => '1', // use cron module
            '_token' => null, // password for cron job

            '_id_customer' => 0, // For shipping cost computation @since 1.2.4.0
            '_id_address' => 0, // For shipping cost computation @since 1.2.4.0
            '_id_cart' => 0, // For shipping cost computation @since 1.2.4.0

            '_model' => '', // @since 1.2.4.0
            '_export' => '', // @since 1.2.4.0
            '_directory' => '/modules/'.$this->name.'/datas/', // @since 1.2.4.0
            '_next_shop' => Configuration::get('PS_SHOP_DEFAULT'),
            'host' => '',
            'advanced' => 0, // display more configuration options
        );
    }

    private function preExportCatalog($echo = true, $current = 0, $limit = 250, $export = null)
    {
        @set_time_limit(600);
        if (function_exists('gc_disable')) {
            gc_disable();
        }

        $cart = $this->context->cart;
        if (!isset($cart)) {
            $cart = new Cart();
        }

        if (!$export) {
            if (Tools::getValue('export')) {
                $export = new Samdha_ExportCatalog_Export(
                    _PS_ROOT_DIR_.$this->config->_directory,
                    $this,
                    Tools::getValue('export')
                );
                $model = $export->getModel();
            } else {
                $model = new Samdha_ExportCatalog_Model(
                    _PS_ROOT_DIR_.$this->config->_directory,
                    $this,
                    Tools::getValue('model')
                );
            }
        } else {
            $model = $export->getModel();
        }

        $host = version_compare(_PS_VERSION_, '1.4.0.0', '<')?$this->config->host:'';
        if (version_compare(_PS_VERSION_, '1.4.0.0', '<') && !isset($_SERVER['HTTP_HOST'])) {
            $url = parse_url($this->config->host);
            $_SERVER['HTTP_HOST'] = $url['host'];
        }
        if (version_compare(_PS_VERSION_, '1.4.0.0', '<') && !defined('_PS_BASE_URL_')) {
            define('_PS_BASE_URL_', $this->config->host);
        }
        $model->setContext();

        if (Tools::getValue('current') !== false) {
            $current = Tools::getValue('current');
        }
        if (Tools::getValue('total')) {
            $total = (int)Tools::getValue('total');
        } else {
            $total = $model->countProducts();
        }

        $products = $model->getProducts($limit);

        if (Tools::getValue('filename')) {
            $filename = $this->tools->decode(Tools::getValue('filename'));
        } else {
            $filename = tempnam(_PS_ROOT_DIR_.$this->config->_directory, $this->name);
        }

        $out = fopen($filename, 'a');

        if ($current == 0) {
            $model->writeHeader($out);
        }

        if (count($products)) {
            $model->writeProducts($out, $products, $host);
        }
        fclose($out);

        if ($echo) {
            if (!count($products)) {
                $next = -1;
            } else {
                $next = $current + count($products);
            }

            echo $this->samdha_tools->jsonEncode(array(
                'status' => 'success',
                'data'   => array(
                    'next'           => $next,
                    'total'          => $total,
                    'filename'       => $this->tools->encode($filename)
                )
            ));
        } else {
            return $filename;
        }
    }

    private function postExportCatalog($echo = true, $export = null, $filename = null)
    {
        @set_time_limit(600);

        if (!$filename) {
            $filename = $this->tools->decode(Tools::getValue('filename'));
        }

        if (!$export) {
            $export = new Samdha_ExportCatalog_Export(
                _PS_ROOT_DIR_.$this->config->_directory,
                $this,
                Tools::getValue('export')
            );
        }

        $export->export($filename);

        if ($echo) {
            echo $this->samdha_tools->jsonEncode(array('status' => 'success'));
        }
    }

    /**
     * called for testing ajax call
     * @return void
     */
    private function ajaxTest()
    {
        echo $this->samdha_tools->jsonEncode(array('status' => 'success'));
    }

    /**
     * scheduled export
     */
    public function cron($from_url = false)
    {
        if (!$from_url
            || ($this->config->_token == Tools::getValue('token'))) {
            $export = new Samdha_ExportCatalog_Export(_PS_ROOT_DIR_.$this->config->_directory, $this);
            $exports = $export->getFiles();
            if (!empty($exports)) {
                $id_shops = array();
                $id_exports = array_keys($exports);
                foreach ($id_exports as $id_export) {
                    $export->loadFromFile($id_export);
                    $id_shop = $export->getModel()->datas['id_shop'];
                    $id_shops[$id_shop] = $id_shop;
                    if (!isset($export->datas['next_run'])) {
                        // should not append
                        $export->save();
                    } elseif ($export->datas['next_run'] <= time()) {
                        $filename = $this->preExportCatalog(false, 0, PHP_INT_MAX, $export);
                        $this->postExportCatalog(false, $export, $filename);
                        echo date('r')."\tExport ".$export->name.PHP_EOL;
                        $export->datas['last_run'] = time();
                        $export->save();
                    }
                }
                // find next shop to export
                if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
                    sort($id_shops);
                    $this->config->_next_shop = $id_shops[0];
                    foreach ($id_shops as $key => $id_shop) {
                        if ($id_shop == Configuration::get('PS_SHOP_DEFAULT')) {
                            if (isset($id_shops[$key + 1])) {
                                $this->config->_next_shop = $id_shops[$key + 1];
                            }
                            break;
                        }
                    }
                }
            }
        } else {
            throw new Exception('Forbidden access');
        }
    }

    /**
     * used by jqueryFileTree
     * called by ajax
     * @return void
     */
    public function getFileTree()
    {
        $dir = Tools::getValue('dir');
        if ($dir == '') {
            echo '<ul class="jqueryFileTree" style="display: none;">';
            if (Tools::getValue('dontsave')) {
                echo '<li><a class="selected file delete" href="#" rel="">'.$this->l('Do not save', 'main').'</a></li>';
            }
            echo '
                    <li>
                        <a class="directory '.(!is_writable(_PS_ROOT_DIR_)?'readonly ':'').'collapsed"
                            href="#" rel="/">'
                        .$this->l('Root', 'main').'
                        </a>
                    </li>
                </ul>';
        } elseif (file_exists(_PS_ROOT_DIR_.$dir)) {
            $files = scandir(_PS_ROOT_DIR_.$dir);
            natcasesort($files);
            if (count($files) > 2) { /* The 2 accounts for . and .. */
                echo '<ul class="jqueryFileTree" style="display: none;">';
                // All dirs
                foreach ($files as $file) {
                    if (file_exists(_PS_ROOT_DIR_.$dir.$file)
                        && $file != '.'
                        && $file != '..'
                        && is_dir(_PS_ROOT_DIR_.$dir.$file)
                    ) {
                        echo '<li>
                            <a
                            class="directory '.(!is_writable(_PS_ROOT_DIR_.$dir.$file)?'readonly ':'').'collapsed"
                            href="#" rel="'.htmlentities($dir.$file).'/">'.htmlentities($file).'</a>
                            </li>';
                    }
                }
                echo '</ul>';
            }
        }
    }
}
