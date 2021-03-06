<?php
/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
 * @file
 * Description of what this module (or file) is doing.
 */
 
if (!defined('_PS_VERSION_')) {
    exit;
}

class MailChimpIntegration extends Module
{
    private $_html = '';
    
    public function __construct()
    {
        $this->name = 'mailchimpintegration';
        $this->tab = 'emailing';
        $this->version = '1.0.1';
        $this->author = 'NuRelm Inc.';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->module_key='793ebc5f330220c7fb7b817fe0d63a92';

        parent::__construct();

        $this->displayName = $this->l('MailChimp Integration');
        $this->description = $this->l('Official MailChimp integration for PrestaShop.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        if (!parent::install() || !$this->registerHook('moduleRoutes') || !$this->registerHook('actionProductAdd') ||
                !$this->registerHook('actionProductUpdate') || !$this->registerHook('actionValidateOrder') ||
                !$this->registerHook('actionOrderStatusUpdate') ||
                !$this->registerHook('actionObjectCustomerAddAfter') || !$this->registerHook('actionObjectCustomerUpdateAfter') ||
                !$this->registerHook('actionObjectCartUpdateAfter') || !$this->registerHook('displayHeader')) {
            return false;
        }
        return true;
    }
    

    public function uninstall()
    {
        if (!parent::uninstall() || !$this->deactivateMailchimpStore() || !$this->unregisterHook('moduleRoutes') || !$this->unregisterHook('actionProductAdd') ||
                !$this->unregisterHook('actionProductUpdate') || !$this->unregisterHook('actionValidateOrder') ||
                !$this->unregisterHook('actionOrderStatusUpdate') ||
                !$this->unregisterHook('actionObjectCustomerAddAfter') || !$this->unregisterHook('actionObjectCustomerUpdateAfter') || !$this->unregisterHook('actionObjectCartUpdateAfter') || !$this->unregisterHook('displayHeader')) {
            return false;
        }
        Configuration::deleteByName('MAILCHIMP_LIST_ID');
        Configuration::deleteByName('MAILCHIMP_API_KEY');
        return true;
    }

    public function hookDisplayHeader($params)
    {
        if (Tools::getValue('utm_source') == 'mailchimp' || Tools::getIsset(Tools::getValue('mc_cid'))) {
            $this->context->cookie->landing_site = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
    }


    public function hookActionObjectCustomerAddAfter($params)
    {
        $store_id = $this->context->shop->id;
        $url = 'ecommerce/stores/'.$store_id.'/customers/';
        $data = $this->formatCustomerArray($params['object']);
        // error_log(print_r($data, true), 3, '/var/log/presta-errors.log');

        $result = $this->sendApiRequest($url, 'POST', $data);
        // error_log(print_r($result, true), 3, '/var/log/presta-errors.log');
    }

    public function hookActionObjectCustomerUpdateAfter($params)
    {
        $store_id = $this->context->shop->id;
        $data = $this->formatCustomerArray($params['object']);
        $url = 'ecommerce/stores/'.$store_id.'/customers/'.$data['id'];
        $result = $this->sendApiRequest($url, 'PATCH', $data);
    }

    public function hookActionObjectCartUpdateAfter($params)
    {
        $this->syncCarts();
        // error_log(print_r($params, true), 3, '/var/log/presta-errors.log');
    }

    public function hookActionOrderStatusUpdate($params)
    {
        $order = new Order($params['id_order']);
        $store_id = $this->context->shop->id;
        $order_id = (string)$params['id_order'];
        $url = 'ecommerce/stores/'.$store_id.'/orders/'.$order_id;
        $data = $this->formatOrderArray($order->getFields());
        $data['financial_status'] = $this->mapOrderStatuses($params['newOrderStatus']->name);
        if ($params['newOrderStatus']->name == 'Shipped') {
            $data['fulfillment_status'] = 'shipped';
        }
        $result = $this->sendApiRequest($url, 'PATCH', $data);
        // error_log(print_r($data, true), 3, '/var/log/presta-errors.log');`??
        // error_log(print_r($result, true), 3, '/var/log/presta-errors.log');
    }

    public function hookActionValidateOrder($params)
    {
        $data = $this->formatOrderArray($params['order']->getFields());
        $store_id = $this->context->shop->id;
        $url = 'ecommerce/stores/'.$store_id.'/orders';
        if (!Tools::getIsset($data['financial_status'])) {
            $data['financial_status'] = 'paid';
        }
        $result = $this->sendApiRequest($url, 'POST', $data);
        $products = $params['order']->getProducts();
        $default_lang = $this->context->language->id;

        foreach ($products as $product) {
            $product['id_product'] = $product['product_id'];
            $data = $this->formatProductArray($product, $default_lang);

            $url = 'ecommerce/stores/'.$store_id.'/products/'.$product['product_id'];

            $result = $this->sendApiRequest($url, 'PATCH', $data);
        }

        $url = 'ecommerce/stores/'.$store_id.'/carts/'.$params['order']->getFields()['id_cart'];
        $result = $this->sendApiRequest($url, 'DELETE');
    }

    public function hookActionProductAdd($params)
    {
        // error_log(print_r($params, true), 3, '/var/log/presta-errors.log');

        $store_id = $this->context->shop->id;
        $url = 'ecommerce/stores/'.$store_id.'/products';
        $default_lang = $this->context->language->id;
        $data = $this->formatProductArray($params, $default_lang);
        // error_log(print_r($data, true), 3, '/var/log/presta-errors.log');

        $result = $this->sendApiRequest($url, 'POST', $data);
        // error_log(print_r($result, true), 3, '/var/log/presta-errors.log');
    }

    public function hookActionProductUpdate($params)
    {
        $store_id = $this->context->shop->id;
        $url = 'ecommerce/stores/'.$store_id.'/products/'.$params['id_product'];
        $default_lang = $this->context->language->id;
        $data = $this->formatProductArray($params, $default_lang);

        $result = $this->sendApiRequest($url, 'PATCH', $data);
        // error_log(print_r($result, true), 3, '/var/log/presta-errors.log');
    }

    public function hookModuleRoutes($params)
    {
        $my_link = array(
          'module-mailchimpintegration-mailchimp' => array(
              'controller' => 'mailchimp',
              'rule' => 'mailchimp_oauth2_redirect',
               'params' => array(
                   'fc' => 'module',
                   'module' => 'mailchimpintegration'
               )
           )
        );
        return $my_link;
    }

    public function getContent()
    {
        $output = null;
        if (Tools::getValue('access_token')) {
            $access_token =  Tools::getValue('access_token');
            $dc = Tools::getValue('dc');
            if (!empty($access_token) && !empty($dc)) {
                Configuration::updateValue('MAILCHIMP_API_KEY', $access_token);
                Configuration::updateValue('MAILCHIMP_DC', $dc);
                $this->context->controller->addCss($this->_path.'/views/css/main.css');
                return $this->display($this->_path, 'views/templates/admin/main.tpl').$this->displayForm();
            }
        }

        if (Tools::isSubmit('submit'.$this->name)) {
            if (!Configuration::get('MAILCHIMP_API_KEY')) {
                $final_url = Tools::getHttpHost(true).__PS_BASE_URI__.basename(_PS_ADMIN_DIR_).'/'.Context::getContext()->link->getAdminLink('AdminModules').'&configure=mailchimpintegration&module_name=mailchimpintegration';
                $redirect_uri = 'http://ps-redirect.nurelm.com/?'.http_build_query(
                    array(
                        'final_url' => $final_url
                    )
                );
                $parameters = array(
                    'redirect_uri'=> $redirect_uri,
                    'client_id'=>'365239281672',
                    'response_type'=>'code');
                $url = 'https://login.mailchimp.com/oauth2/authorize?';
                $url .= http_build_query($parameters);

                return Tools::redirect($url);
            }
            if (Configuration::get('MAILCHIMP_LIST_ID')) {
                Configuration::deleteByName('MAILCHIMP_LIST_ID');
                $store_id = $this->context->shop->id;
                $url = 'ecommerce/stores/'.$store_id;
                $this->sendApiRequest($url, 'DELETE');
            } elseif (Tools::getValue('current_list_id') &&  Tools::getValue('current_list_id') != -1) {
                Configuration::updateValue('MAILCHIMP_LIST_ID', Tools::getValue('current_list_id'));
                $list_result = $this->sendApiRequest('lists/'.Tools::getValue('current_list_id'), 'GET');
                Configuration::updateValue('MAILCHIMP_LIST_NAME', $list_result['name']);
                $result = $this->initialDataSync();
            } elseif (Tools::getValue('new_list_name')) {
                $list_name = Tools::getValue('new_list_name');

                // TODO enforce mailchimp validations
                if (!$list_name || empty($list_name)) {
                    $output .= $this->displayError('Invalid List name');
                } else {
                    $response = $this->createMailchimpList($list_name);
                    if ($response) {
                        Configuration::updateValue('MAILCHIMP_LIST_NAME', $list_name);
                        $result = $this->initialDataSync();
                    } else {
                        $error_message = 'List Creation failed. Please check that Store Address exists.';
                        $error_message .= ' A physical address is required to create a list. More here: http://eepurl.com/b2Q7Sb';
                        $output .= $this->displayError($error_message);
                    }
                }
            }
        }
        if (Configuration::get('MAILCHIMP_LIST_ID')) {
            $this->context->smarty->assign('list_name', Configuration::get('MAILCHIMP_LIST_NAME'));
        }
        $this->context->controller->addCss($this->_path.'/views/css/main.css');
        return $this->display($this->_path, 'views/templates/admin/main.tpl').$output.$this->displayForm();
    }

    public function displayForm()
    {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $prestashop_auth_code = Tools::getValue("token");

        $api_key = Configuration::get('MAILCHIMP_API_KEY');

        if (!$api_key) {
            $this->fields_form[0]['form']['submit'] = array(
                'title' => 'Connect Your Store',
                'class' => 'btn btn-primary'
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

            return $helper->generateForm($this->fields_form);
        } else {
            $this->fields_form[0]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Setup MailChimp integration')
                )
            );

            if (Configuration::get('MAILCHIMP_LIST_ID')) {
                $this->fields_form[0]['form']['submit'] = array(
                    'title' => $this->l('Disconnect from List'),
                    'class' => 'btn btn-default'
                );
            } else {
                $current_lists = $this->sendApiRequest('lists?count=100', 'GET');
                $options = array();
                $options[] = array(
                    'id' => -1,
                    'name' => 'Select a List'
                );


                foreach ($current_lists['lists'] as $list) {
                    $options[] = array(
                        'id' => $list['id'],
                        'name' => $list['name']
                    );
                }


                $select_list = array(
                    'type' => 'select',
                    'name' => 'current_list_id',
                    'label' => $this->l('Select an existing List'),

                    'options' => array(
                        'query' => $options,
                        'id' => 'id',
                        'name' => 'name'
                    )
                );

                $this->fields_form[0]['form']['input'][] = $select_list;

                $this->fields_form[0]['form']['input'][] = array(
                    'type' => 'text',
                    'name' => 'new_list_name',
                    'label' => $this->l('...or create a new MailChimp List'),
                    'desc' => 'Name your List',
                );
                $this->fields_form[0]['form']['submit'] = array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default'
                );
            }
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
            $helper->fields_value['current_list_id'] = '';
            $helper->fields_value['new_list_name'] = '';
            return $helper->generateForm($this->fields_form);
        }
    }

    private function syncCustomers()
    {
        $store_id = $this->context->shop->id;
        $url = 'ecommerce/stores/'.$store_id.'/customers/';
        $errors = array();
        // TODO sync as much data here as possible, e.g. total orders, total spent
        $sql = 'SELECT `id_customer`
				FROM `'._DB_PREFIX_.'customer`
				WHERE 1 '.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER).
                ' AND `active` = 1'.'
				ORDER BY `id_customer` ASC';
        $customers = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        foreach ($customers as $customer) {
            $customer = new Customer($customer['id_customer']);
            $data = $this->formatCustomerArray($customer);
            $result = $this->sendApiRequest($url, 'POST', $data);

            if (array_key_exists('errors', $result)) {
                foreach ($result['errors'] as $error) {
                    $result[] = $error['message'];
                }
            }
        }
        return $errors;
    }

    private function initialDataSync()
    {
        // TODO error handle these
        $this->createMailchimpStore();
        $this->syncCustomers();
        $this->syncProducts();
        $this->syncOrders();
        $this->syncCarts();
        $this->activateMailchimpStore();
    }

    private function activateMailchimpStore()
    {
        $store_id = $this->context->shop->id;
        $url = 'ecommerce/stores/'.$store_id;
        $data = array(
            'is_syncing' => false
        );

        
        $result = $this->sendApiRequest($url, 'PATCH', $data);
    }

    private function deactivateMailchimpStore()
    {
        $store_id = $this->context->shop->id;
        $url = 'ecommerce/stores/'.$store_id;
        $data = array(
            'is_active' => false
        );

        
        $result = $this->sendApiRequest($url, 'PATCH', $data);
        // error_log(print_r($result, true), 3, '/var/log/presta-errors.log');
        return true;
    }

    private function formatCartArray($cart_hash)
    {
        $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $store_currency_format = CurrencyCore::getCurrenciesByIdShop($this->context->shop->id)[0]['iso_code'];
        $cart = new Cart($cart_hash['id_cart']);
        $products = $cart->getProducts();
        $store_id = $this->context->shop->id;
        $url = 'ecommerce/stores/'.$store_id.'/carts';
        $cart_url = Tools::getHttpHost(true).__PS_BASE_URI__.'?id_cart='.(int)$cart_hash['id_cart'].'&id_customer='.(int)$cart_hash['id_customer'];
        $customer = new Customer($cart_hash['id_customer']);
        $cart_data = array(
                'id' => (string)$cart_hash['id_cart'],
                'customer' => $this->formatCustomerArray($customer),
                'order_total' => (float)$cart->getOrderTotal(),
                'checkout_url' => $cart_url,
                'currency_code' => $store_currency_format,
                'lines' => array()
            );
        foreach ($products as $prod) {
            $p = new Product($prod['id_product'], true, $id_lang);
            $price_with_tax = (Product::getPriceStatic($p->id, true, null, 2, null, false, true, 1, false, null, $cart_hash['id_cart']));
            $total_with_tax = $prod['cart_quantity'] * $price_with_tax;
                
            $cart_data['lines'][] = array(
                    'id' => (string)$prod['unique_id'],
                    'product_id' => (string)$prod['id_product'],
                    'product_variant_id' => (string)($prod['id_product_attribute'] == 0 ? $prod['id_product'] : $prod['id_product_attribute']),
                    'quantity' => (int)$prod['cart_quantity'],
                    'price' => $total_with_tax
                );
        }

        return $cart_data;
    }

    private function syncCarts()
    {
        $cur_cart = Context::getContext()->cart;
        if (is_object($cur_cart)) {
            // get abandoned cart :
            $store_currency_format = CurrencyCore::getCurrenciesByIdShop($this->context->shop->id)[0]['iso_code'];
            $sql = "SELECT * FROM (
            SELECT
            c.firstname, c.lastname, c.email email, c.optin,  a.id_cart total, ca.name carrier, c.id_customer id_customer, a.id_cart, a.date_upd,a.date_add, IFNULL(o.id_order, 'Non ordered') id_order
            FROM "._DB_PREFIX_."cart a  
                    LEFT JOIN "._DB_PREFIX_."customer c ON (c.id_customer = a.id_customer)
                    LEFT JOIN "._DB_PREFIX_."currency cu ON (cu.id_currency = a.id_currency)
                    LEFT JOIN "._DB_PREFIX_."carrier ca ON (ca.id_carrier = a.id_carrier)
                    LEFT JOIN "._DB_PREFIX_."orders o ON (o.id_cart = a.id_cart)
                    ORDER BY a.date_upd DESC LIMIT 1
            ) AS toto WHERE id_order='Non ordered' AND id_customer IS NOT NULL";

            $sql_result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
            $data = array();
            $store_id = $this->context->shop->id;

            foreach ($sql_result as $cart_hash) {
                $url = 'ecommerce/stores/'.$store_id.'/carts';
                $cart_data = $this->formatCartArray($cart_hash);

                $result = $this->sendApiRequest($url, 'POST', $cart_data);

                if (array_key_exists('status', $result) && $result['status'] == 400) {
                    $url .= '/'.$cart_hash['id_cart'];
                    $result = $this->sendApiRequest($url, 'PATCH', $cart_data);
                }
            }
        }
    }

    private function formatOrderArray($order)
    {

        $store_currency_format = CurrencyCore::getCurrenciesByIdShop($this->context->shop->id)[0]['iso_code'];
        $store_id = $this->context->shop->id;
        $customer = new Customer($order['id_customer']);


        $shipping_address = new Address($order['id_address_delivery']);
        $billing_address = new Address($order['id_address_invoice']);

        $data = array(
            'id' => (string)$order['id_order'],
            'currency_code' => $store_currency_format,
            'order_total' => $order['total_paid'],
            'tax_total' => $order['total_paid_tax_incl'] - $order['total_paid_tax_excl'],
            'shipping_total' => $order['total_shipping'],
            'shipping_address' => $this->formatAddressArray($shipping_address),
            'billing_address' => $this->formatAddressArray($billing_address),
            'lines' => array(),
            'customer' => $this->formatCustomerArray($customer, $shipping_address)
        );

        if (Tools::getIsset($this->context->cookie->landing_site)) {
            $data['landing_site'] = $this->context->cookie->landing_site;
        }

        $data['processed_at_foreign'] = date('Y-m-d H:i:s');


        if (!empty($order['shipping_number'])) {
            $data['tracking_code'] = $order['shipping_number'];
        }

        $order_object = new Order($order['id_order']);
        $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $data['financial_status'] = $this->mapOrderStatuses($order_object->getCurrentStateFull($id_lang)['name']);
        $lines = $order_object->getProductsDetail();

        foreach ($lines as $line) {
            // error_log(print_r($line, true), 3, '/var/log/presta-errors.log');

            $data['lines'][] = array(
                'id' => $line['id_order_detail'],
                'product_id' => $line['product_id'],
                'product_variant_id' => ($line['product_attribute_id'] == 0 ? $line['product_id'] : $line['product_attribute_id']),
                'quantity' => (int)$line['product_quantity'],
                'price' => $line['price']
            );
        }



        return $data;
    }

    private function mapOrderStatuses($status)
    {
        if ($status == 'Payment accepted' || $status == 'Remote payment accepted') {
            return 'pending';
        }
        if ($status == 'Canceled') {
            return 'cancelled';
        }
        if ($status == 'Refunded') {
            return 'refunded';
        }
        if ($status == 'Shipped') {
            return 'shipped';
        }
        
        return $status;
    }

    private function syncOrders()
    {
        $orders = OrderCore::getOrdersWithInformations();
        $store_currency_format = CurrencyCore::getCurrenciesByIdShop($this->context->shop->id)[0]['iso_code'];
        $store_id = $this->context->shop->id;
        $url = 'ecommerce/stores/'.$store_id.'/orders';
        foreach ($orders as $order) {
            $data = $this->formatOrderArray($order);

            $result = $this->sendApiRequest($url, 'POST', $data);
        }
    }

    public function formatCustomerArray($customer, $address = array())
    {
        $data = array(
            'id' => (string)$customer->id,
            'email_address' => $customer->email,
            'opt_in_status' => (bool)($customer->optin || $customer->newsletter),
            'first_name' => $customer->firstname,
            'last_name' => $customer->lastname
        );



        if (!empty($address)) {
            $data['address'] = $this->formatAddressArray($address);
        }

        if (!empty($customer->company)) {
            $data['company'] = $customer->company;
        }

        $sql = "SELECT count(o.id_order) as ordercnt, sum(o.total_paid) as ordertotal
		        FROM "._DB_PREFIX_."customer c  
				LEFT JOIN "._DB_PREFIX_."orders o ON (o.id_customer = c.id_customer)
				WHERE (c.id_customer = ".pSQL($customer->id).") GROUP BY o.id_customer";

        $sql_result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        $data['orders_count'] = (int)$sql_result[0]['ordercnt'];
        $data['total_spent'] =  (Tools::getIsset($sql_result[0]['ordertotal']) ? $sql_result[0]['ordertotal'] : 0.0);
        return $data;
    }

    private function syncProducts()
    {
        $products = ProductCore::getProducts($this->context->language->id, 0, 0, 'id_product', 'DESC');
        $store_id = $this->context->shop->id;
        $url = 'ecommerce/stores/'.$store_id.'/products';
        $default_lang = $this->context->language->id;

        foreach ($products as $product) {
            $data = $this->formatProductArray($product, $default_lang);
            $result = $this->sendApiRequest($url, 'POST', $data);
        }
    }

    public function formatProductArray($product, $default_lang)
    {
        $store_id = $this->context->shop->id;
        $default_lang = $this->context->language->id;

        $product = new Product($product['id_product']);
        $variants = $product->getAttributeCombinations($default_lang);

        $category = new Category($product->getDefaultCategory());
        $cat_name = $category->name;
        $images = $product->getImages($default_lang);
        $image = Image::getCover($product->id);
        $link = new Link;
        $imagePath = 'http://'.$link->getImageLink($product->link_rewrite['1'], $image['id_image'], ImageType::getFormatedName('home'));
        $product_url = $product->getLink();
        $data = array(
                'id' => (string)$product->id,
                'title' => $product->name['1'],
                'description' => $product->description['1'],
                'url' => $product_url,
                'type' => $cat_name['1'],
                'image_url' => $imagePath,
                'variants' => array()

            );
        $variants_data = array();
        $variants_so_far = array();
        foreach ($variants as $variant) {
            if (array_key_exists($variant['id_product_attribute'], $variants_so_far)) {
                $variants_so_far[$variant['id_product_attribute']]['title'] .= ' ('.$variant['group_name'].': '.$variant['attribute_name'].')';
            } else {
                $variant_upc = (empty($variant['upc']) || !$variant['upc']) ? $product->upc : $variant['upc'];
                $combination = new Combination($variant['id_product_attribute']);
                $image = Image::getBestImageAttribute($store_id, $default_lang, $product->id, $variant['id_product_attribute']);

                $link = new Link;
                $imagePath = 'http://'.$link->getImageLink($product->link_rewrite['1'], $image['id_image']);

                $variants_so_far[$variant['id_product_attribute']] = array(
                        'title' => $product->name['1'].' ('.$variant['group_name'].': '.$variant['attribute_name'].')',
                        'upc' => $combination->upc,
                        'price' => Product::getPriceStatic($product->id, true, $variant['id_product_attribute']),
                        'inventory_quantity' => $variant['quantity'],
                        'image_url' => $imagePath
                    );
            }
        }

        foreach ($variants_so_far as $key => $value) {
            $data['variants'][] = array(
                    'id' => (string)$key,
                    'title' => $value['title'],
                    'sku' => $value['upc'],
                    'price' => $value['price'],
                    'inventory_quantity' => $value['inventory_quantity'],
                    'image_url' => $value['image_url']
                );
        }

        if (count($data['variants']) == 0) {
            $data['variants'][] = array(
                    'id' => $data['id'],
                    'title' => $data['title'],
                    'inventory_quantity' => (int)Product::getRealQuantity($product->id),
                    'price' => Product::getPriceStatic($product->id),
                    'sku' => $product->upc
                );
        }


        return $data;
    }

    public function formatStoreArray()
    {
        $store_name = $this->context->shop->name;
        $store_id = ShopCore::getIdByName($store_name);

        $store_address = $this->context->shop->getAddress();
        $store_state = (StateCore::getNameById($store_address->id_state) ? StateCore::getNameById($store_address->id_state) : '');
        $store_country_iso = CountryCore::getIsoById($store_address->id_country);
        $store_currency_iso = CurrencyCore::getCurrenciesByIdShop($store_id)[0]['iso_code'];
        $store_currency_format = CurrencyCore::getCurrenciesByIdShop($store_id)[0]['sign'];
        //TODO if address is not set, this fails.
        $data = array(
            'id' => (string)$store_id,
            'name' => $store_name,
            'address' => array(
                'company' => $store_address->company,
                'address1' => $store_address->address1,
                'address2' => (Tools::getIsset($store_address->address2) ? $store_address->address2 : ''),
                'city' => $store_address->city,
                'state' => $store_state,
                'country' => $store_country_iso,
                'zip' => $store_address->postcode
            ),
            'list_id' => Configuration::get('MAILCHIMP_LIST_ID'),
            'platform' => 'PrestaShop',
            'domain' => Tools::getHttpHost(true).__PS_BASE_URI__,
            'is_syncing' => true,
            'email_address' => (string)Configuration::get('PS_SHOP_EMAIL'),
            'currency_code' => $store_currency_iso,
            'money_format' => $store_currency_format
        );

        return $data;
    }

    private function createMailchimpStore()
    {
        $store_name = $this->context->shop->name;
        $store_id = ShopCore::getIdByName($store_name);

        $store_address = $this->context->shop->getAddress();
        $store_state = (StateCore::getNameById($store_address->id_state) ? StateCore::getNameById($store_address->id_state) : '');
        $store_country_iso = CountryCore::getIsoById($store_address->id_country);
        $store_currency_iso = CurrencyCore::getCurrenciesByIdShop($store_id)[0]['iso_code'];
        $store_currency_format = CurrencyCore::getCurrenciesByIdShop($store_id)[0]['sign'];
        //TODO if address is not set, this fails.
        $data = $this->formatStoreArray();

        $result = $this->sendApiRequest('ecommerce/stores', 'POST', $data);

        return true;
    }

    public function sendApiRequest($url, $method, $data = array())
    {
        $url = $this->getApiUrl().$url;
        $access_token = Configuration::get('MAILCHIMP_API_KEY');
        $headers = array(
            'Content-Type: application/json',
            "Authorization: OAuth $access_token"
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method == 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        $output = curl_exec($ch);

        $output = json_decode($output, true);



        curl_close($ch);

        return $output;
    }

    private function formatAddressArray($address)
    {
        $state = StateCore::getNameById($address->id_state);

        $country_iso = CountryCore::getIsoById($address->id_country);

        $formatted_address = array(
            'company' => $address->company,
            'address1' => $address->address1,
            'address2' => (Tools::getIsset($address->address2) ? $address->address2 : ''),
            'city' => $address->city,
            'state' => ($state ? $state : ''),
            'country' => $country_iso,
            'zip' => $address->postcode
        );
        return $formatted_address;
    }

    private function createMailchimpList($list_name)
    {
        $store_name = $this->context->shop->name;
        $shop_email = (string)Configuration::get('PS_SHOP_EMAIL');


        $store_address = $this->context->shop->getAddress();
        $store_state = StateCore::getNameById($store_address->id_state);
        $store_country_iso = CountryCore::getIsoById($store_address->id_country);

        $data = array(
            'name' => $list_name,
            'contact' => array(
                'company' => $store_address->company,
                'address1' => $store_address->address1,
                'address2' => (Tools::getIsset($store_address->address2) ? $store_address->address2 : ''),
                'city' => $store_address->city,
                'state' => ($store_state ? $store_state : ''),
                'country' => $store_country_iso,
                'zip' => $store_address->postcode
            ),
            'campaign_defaults' => array(
                'from_name' => $store_name,
                'from_email' => $shop_email,
                'subject' => $store_name,
                'language' => 'en'
            ),
            'permission_reminder' => 'You are receiving this email because you signed up for an account at the following store: '.$store_name,
            'email_type_option' => true
        );


        $result = $this->sendApiRequest('lists', 'POST', $data);


        if ($result === false) {
            return false;
        } else {
            if (array_key_exists('id', $result)) {
                Configuration::updateValue('MAILCHIMP_LIST_NAME', $list_name);
                Configuration::updateValue('MAILCHIMP_LIST_ID', $result['id']);
                return $result;
            } else {
                return false;
            }
        }
    }

    public function getApiUrl()
    {
        return 'http://'.Configuration::get('MAILCHIMP_DC').'.api.mailchimp.com/3.0/';
    }
}
