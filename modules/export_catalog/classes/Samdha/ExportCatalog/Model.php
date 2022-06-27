<?php
/**
 * Export model configuration use by the module export_catalog
 *
 * @category  Prestashop
 * @category  Module
 * @author    Samdha <contact@samdha.net>
 * @copyright Samdha
 * @license   commercial license see license.txt
 */

class Samdha_ExportCatalog_Model extends Samdha_ExportCatalog_Configuration
{
    protected $extension = '.model';

    /**
     * load configuration from a file
     * @param  string $filename file to load
     * @return boolean           true if succeeded
     */
    public function loadFromFile($filename)
    {
        $result = parent::loadFromFile($filename);
        if ($result) {
            // set default values for old configurations
            if (!isset($this->datas['categories']) || !is_array($this->datas['categories'])) {
                $this->datas['categories'] = array();
            }
            if (!isset($this->datas['manufacturers']) || !is_array($this->datas['manufacturers'])) {
                $this->datas['manufacturers'] = array();
            }
            if (!isset($this->datas['suppliers']) || !is_array($this->datas['suppliers'])) {
                $this->datas['suppliers'] = array();
            }
            if (!isset($this->datas['price_format'])) {
                $this->datas['price_format'] = '';
            }
            if (!isset($this->datas['new'])) {
                $this->datas['new'] = '0';
            }
            if (!isset($this->datas['last_run'])) {
                $this->datas['last_run'] = '0';
            }
            if (!isset($this->datas['enclosure'])) {
                $this->datas['enclosure'] = '"';
            }
            if (!isset($this->datas['force_enclosure'])) {
                $this->datas['force_enclosure'] = '0';
            }
            if (!isset($this->datas['with_ean'])) {
                $this->datas['with_ean'] = '1';
            }
        }
        return $result;
    }

    /**
     * delete configuration file from disk
     *
     * @return void
     */
    public function delete()
    {
        // delete linked exports
        $export = new Samdha_ExportCatalog_Export($this->directory, $this->module);
        $filenames = array_keys($export->getFiles());
        foreach ($filenames as $filename) {
            $export->loadFromFile($filename);
            if ($export->datas['model'] == $this->filename) {
                $export->delete();
            }
        }

        return parent::delete();
    }

    /**
     * set default configuration
     * @return void
     */
    protected function initDefault()
    {
        $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $this->name = '';
        $this->datas = array(
            'filename' => 'catalog_%Y-%m-%d.csv',
            'separator' => ',',
            'enclosure' => '"',
            'force_enclosure' => '0',
            'header' => '1',
            'charset' => 'ISO-8859-15',
            'fields' => array(
                array(
                    'id' => 'reference',
                    'title' => $this->module->l('Reference', 'model', $id_lang),
                    'value' => '',
                    'before' => '',
                    'after' => ''
                ),
                array(
                    'id' => 'ean13',
                    'title' => $this->module->l('EAN13', 'model', $id_lang),
                    'value' => '',
                    'before' => '',
                    'after' => ''
                ),
                array(
                    'id' => 'full_name',
                    'title' => $this->module->l('Name + Combination name', 'model', $id_lang),
                    'value' => '',
                    'before' => '',
                    'after' => ''
                ),
                array(
                    'id' => 'image_url',
                    'title' => $this->module->l('Default image URL', 'model', $id_lang),
                    'value' => '',
                    'before' => '',
                    'after' => ''
                ),
                array(
                    'id' => 'quantity',
                    'title' => $this->module->l('Quantity', 'model', $id_lang),
                    'value' => '',
                    'before' => '',
                    'after' => ''
                ),
                array(
                    'id' => 'price',
                    'title' => $this->module->l('Retail price', 'model', $id_lang),
                    'value' => '',
                    'before' => '',
                    'after' => ''
                )
            ),
            'inactive' => '0',
            'attribute' => '1',
            'new' => '0',
            'no_stock' => '1',
            'with_ean' => '1',
            'size' => '0',
            'categories' => array(),
            'manufacturers' => array(),
            'suppliers' => array(),
            'decimal' => '.',
            'precision' => '2',
            'price_format' => '',
            'decoration' => '0',
            'id_lang' => $id_lang,
            'id_currency' => (int) Configuration::get('PS_CURRENCY_DEFAULT'),
            'id_shop' => (int) Configuration::get('PS_SHOP_DEFAULT'),
            'id_group' => '1',
            'id_country' => (int) Configuration::get('PS_COUNTRY_DEFAULT'),
            'simple_shipping' => '1',
            'last_run' => '0',
        );
    }

    /**
     * say if a field has be exported
     * @param  string  $id the fields key
     * @return boolean     true if the field need to be exported
     */
    public function hasField($id)
    {
        static $fields = null;
        if (is_null($fields)) {
            $fields = array();
            foreach ($this->datas['fields'] as $field) {
                $fields[] = $field['id'];
            }
        }
        return in_array($id, $fields);
    }

    /**
     * set prestashop context before exporting products
     * update shop, lang, country, currency
     */
    public function setContext()
    {
        $need_redirect = false;
        $context = $this->module->context;
        $cookie = $context->cookie;
        $cart = $context->cart;
        if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
            // should append only when called from admin
            if (Configuration::get('PS_SHOP_DEFAULT') != $this->datas['id_shop']) {
                $cookie->old_default_shop = Configuration::get('PS_SHOP_DEFAULT');
                $cookie->old_shop_context = $cookie->shopContext;
                Configuration::updateValue('PS_SHOP_DEFAULT', $this->datas['id_shop']);
                $cookie->shopContext = 's-'.$this->datas['id_shop'];
                $_GET['id_shop'] = $this->datas['id_shop']; // not needed but for security
                $need_redirect = true;
            }
        }

        // set lang
        if (version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
            if ($cookie->id_lang != (int) $this->datas['id_lang']) {
                $cookie->old_id_lang = $cookie->id_lang;
                $cookie->id_lang = (int) $this->datas['id_lang'];
            }
        }

        // set currency
        if (version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
            if (Validate::isLoadedObject($cart)) {
                if ($cart->id_currency != (int) $this->datas['id_currency']) {
                    $cookie->old_id_currency = $cart->id_currency;
                    $cart->id_currency = (int) $this->datas['id_currency'];
                }
            } elseif ($cookie->id_currency != (int) $this->datas['id_currency']) {
                $cookie->old_id_currency = $cookie->id_currency;
                $cookie->id_currency = (int) $this->datas['id_currency'];
            }
        } else {
            if (Validate::isLoadedObject($context->currency)) {
                $cookie->old_id_currency = $context->currency->id;
                $context->currency = new Currency((int) $this->datas['id_currency']);
            } else {
                $context->currency = new Currency((int) $this->datas['id_currency']);
            }
            if (Validate::isLoadedObject($context->country)) {
                if ($context->country->id != $this->datas['id_country']) {
                    $cookie->old_id_country = $context->country->id;
                    $context->country = new Country((int) $this->datas['id_country']);
                }
            } else {
                $context->country = new Country((int) $this->datas['id_country']);
            }
        }

        // set customer
        $customer = $this->module->tools->getFakeCustomer($this->datas['id_group'], $this->datas['id_shop']);
        $cookie->old_id_customer = $cookie->id_customer;
        $cookie->id_customer = $customer->id;
        $context->customer = $customer;

        if ($need_redirect) {
            $query = array_merge($_GET, $_POST);
            $url = parse_url($_SERVER['REQUEST_URI']);
            Tools::redirectAdmin($url['path'].'?'.http_build_query($query, '', '&'));
        }
    }

    /**
     * restore Prestashop context after exporting products
     * restore shop, lang, country, currency
     */
    public function restoreContext()
    {
        $need_redirect = false;
        $context = $this->module->context;
        $cookie = $context->cookie;
        $cart = $context->cart;
        if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
            if (isset($cookie->old_default_shop)) {
                Configuration::updateValue('PS_SHOP_DEFAULT', $cookie->old_default_shop);
                unset($cookie->old_default_shop);
                $need_redirect = true;
            }

            if (isset($cookie->old_shop_context)) {
                $cookie->shopContext = $cookie->old_shop_context;
                unset($cookie->old_shop_context);
                $need_redirect = true;
            }
        }

        // restore lang
        if (isset($cookie->old_id_lang)) {
            $cookie->id_lang = $cookie->old_id_lang;
            unset($cookie->old_id_lang);
        }

        // restore currency
        if (isset($cookie->old_id_currency)) {
            if (version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
                if (Validate::isLoadedObject($cart)) {
                    $cart->id_currency = $cookie->old_id_currency;
                } else {
                    $cookie->id_currency = $cookie->old_id_currency;
                }
            } else {
                $context->currency = new Currency($cookie->old_id_currency);
            }
            unset($cookie->old_id_currency);
        }

        // restore country
        if (isset($cookie->old_id_country)) {
            $context->country = new Country($cookie->old_id_country);
            unset($cookie->old_id_country);
        }

        // restore customer
        if (isset($cookie->old_id_customer)) {
            $cookie->id_customer = $cookie->old_id_customer;
            if (!is_null($this->module->context->customer)) {
                $this->module->context->customer = new Customer($cookie->id_customer);
            }
            unset($cookie->old_id_customer);
        }

        if ($need_redirect) {
            $query = array_merge($_GET, $_POST);
            $url = parse_url($_SERVER['REQUEST_URI']);
            Tools::redirectAdmin($url['path'].'?'.http_build_query($query, '', '&'));
        }
    }

    /**
     * count exportable products
     * @return integer
     */
    public function countProducts()
    {
        $use_shop = version_compare(_PS_VERSION_, '1.5.0.0', '>=');
        $multiple_suppliers = version_compare(_PS_VERSION_, '1.5.3.0', '>=');

        $sql = 'SELECT p.`id_product` ';
        if ($this->datas['attribute']) {
            $sql .= ', pa.`id_product_attribute` ';
        }
        if ($use_shop && $this->datas['no_stock'] != 1) {
            $sql .= ', SUM(sa.`quantity`) AS quantity ';
        }
        $sql .= ' FROM `'._DB_PREFIX_.'product` p ';
        if ($use_shop) {
            $sql .= Shop::addSqlAssociation('product', 'p');
        }
        if (!empty($this->datas['categories'])) {
            $sql .= ' INNER JOIN `'._DB_PREFIX_.'category_product` c ON (c.`id_product` = p.`id_product`) ';
        }

        if ($this->datas['attribute']) {
            $sql .= ' INNER JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.id_product = pa.id_product) ';
            if ($use_shop) {
                $sql .= Shop::addSqlAssociation('product_attribute', 'pa');
            }
            if ($multiple_suppliers && !empty($this->datas['suppliers'])) {
                $sql .= '
                    INNER JOIN `'._DB_PREFIX_.'product_supplier` ps ON (
                        ps.`id_product` = p.`id_product`
                        AND ps.`id_product_attribute` = pa.`id_product_attribute`
                    ) ';
            }
        } elseif ($multiple_suppliers && !empty($this->datas['suppliers'])) {
            $sql .= ' INNER JOIN `'._DB_PREFIX_.'product_supplier` ps ON (ps.`id_product` = p.`id_product`) ';
        }

        if ($this->datas['no_stock'] != 1 && $use_shop) {
            $sql .= ' INNER JOIN `'._DB_PREFIX_.'stock_available` sa ON (sa.id_product = p.id_product) ';
            if ($this->datas['attribute']) {
                $sql .= ' AND (sa.id_product_attribute = pa.id_product_attribute) ';
            }
            // silly isn't it
            $tmp_query = new DbQuery();
            $tmp_query->from('stock_available');
            StockAvailable::addSqlShopRestriction($tmp_query, null, 'sa');
            $tmp_sql = $tmp_query->build();
            $sql .= ' AND '.Tools::substr($tmp_sql, strpos($tmp_sql, 'WHERE ') + 5);
        }
        $sql .= ' WHERE 1 ';
        if ($use_shop) {
            if ($this->datas['inactive'] == 0) {
                $sql .= ' AND product_shop.`active` = 1 ';
            } elseif ($this->datas['inactive'] == 2) {
                $sql .= ' AND product_shop.`active` = 0 ';
            }
            $sql .= ' AND product_shop.`visibility` IN ("both", "catalog") ';
        } else {
            if ($this->datas['inactive'] == 0) {
                $sql .= ' AND p.`active` = 1 ';
            } elseif ($this->datas['inactive'] == 2) {
                $sql .= ' AND p.`active` = 0 ';
            }
            if ($this->datas['no_stock'] == 0) {
                $sql .= ' AND '.($this->datas['attribute']?'pa':'p').'.`quantity` > 0 ';
            }
            if ($this->datas['no_stock'] == 2) {
                $sql .= ' AND '.($this->datas['attribute']?'pa':'p').'.`quantity` = 0 ';
            }
        }
        if (!empty($this->datas['categories'])) {
            $sql .= ' AND c.`id_category` IN ('.implode(',', $this->datas['categories']).') ';
        }
        if (!empty($this->datas['manufacturers'])) {
            $sql .= ' AND p.`id_manufacturer` IN ('.implode(',', $this->datas['manufacturers']).') ';
        }
        if (!empty($this->datas['suppliers'])) {
            if ($multiple_suppliers) {
                $sql .= ' AND ps.`id_supplier` IN ('.implode(',', $this->datas['suppliers']).') ';
            } else {
                $sql .= ' AND p.`id_supplier` IN ('.implode(',', $this->datas['suppliers']).') ';
            }
        }
        if ($this->datas['new'] && $this->datas['last_run']) {
            $sql .= ' AND p.`date_add` > \''.date('Y-m-d H:i:s', $this->datas['last_run']).'\' ';
        }
        if ($this->datas['attribute']) {
            if ($this->datas['attribute'] == 2) {
                $sql .= 'AND pa.`id_product` IS NOT NULL ';
            }
            if ($this->datas['with_ean'] == 0) {
                $sql .= 'AND pa.`ean13` IN (\'0\', \'\') ';
            }
            if ($this->datas['with_ean'] == 2) {
                $sql .= 'AND pa.`ean13` NOT IN (\'0\', \'\') ';
            }
        } else {
            if ($this->datas['with_ean'] == 0) {
                $sql .= 'AND p.`ean13` IN (\'0\', \'\') ';
            }
            if ($this->datas['with_ean'] == 2) {
                $sql .= 'AND p.`ean13` NOT IN (\'0\', \'\') ';
            }
        }

        $sql .= 'GROUP BY  p.`id_product` ';
        if ($this->datas['attribute']) {
            $sql .= ', pa.`id_product_attribute` ';
        }
        if ($use_shop) {
            if ($this->datas['no_stock'] == 0) {
                $sql .= ' HAVING `quantity` > 0 ';
            }
            if ($this->datas['no_stock'] == 2) {
                $sql .= ' HAVING `quantity` = 0 ';
            }
        }
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        $result = count($products);

        if ($this->datas['attribute'] == 1) {
            $sql = 'SELECT p.`id_product` ';
            if ($use_shop && $this->datas['no_stock'] != 1) {
                $sql .= ', SUM(sa.`quantity`) AS quantity ';
            }
            $sql .= ' FROM `'._DB_PREFIX_.'product` p ';
            if ($use_shop) {
                $sql .= Shop::addSqlAssociation('product', 'p');
            }
            if (!empty($this->datas['categories'])) {
                $sql .= ' INNER JOIN `'._DB_PREFIX_.'category_product` c ON (c.`id_product` = p.`id_product`) ';
            } elseif ($multiple_suppliers && !empty($this->datas['suppliers'])) {
                $sql .= ' INNER JOIN `'._DB_PREFIX_.'product_supplier` ps ON (ps.`id_product` = p.`id_product`) ';
            }
            $sql .= ' LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.id_product = pa.id_product) ';
            if ($this->datas['no_stock'] != 1 && $use_shop) {
                $sql .= ' INNER JOIN `'._DB_PREFIX_.'stock_available` sa ON (sa.id_product = p.id_product) ';
                // silly isn't it
                $tmp_query = new DbQuery();
                $tmp_query->from('stock_available');
                StockAvailable::addSqlShopRestriction($tmp_query, null, 'sa');
                $tmp_sql = $tmp_query->build();
                $sql .= ' AND '.Tools::substr($tmp_sql, strpos($tmp_sql, 'WHERE ') + 5);
            }

            $sql .= ' WHERE (pa.id_product IS NULL) ';
            if ($use_shop) {
                if ($this->datas['inactive'] == 0) {
                    $sql .= ' AND product_shop.`active` = 1 ';
                } elseif ($this->datas['inactive'] == 2) {
                    $sql .= ' AND product_shop.`active` = 0 ';
                }
                $sql .= ' AND product_shop.`visibility` IN ("both", "catalog") ';
            } else {
                if ($this->datas['inactive'] == 0) {
                    $sql .= ' AND p.`active` = 1 ';
                } elseif ($this->datas['inactive'] == 2) {
                    $sql .= ' AND p.`active` = 0 ';
                }
                if ($this->datas['no_stock'] == 0) {
                    $sql .= ' AND p.`quantity` > 0 ';
                }
                if ($this->datas['no_stock'] == 2) {
                    $sql .= ' AND p.`quantity` = 0 ';
                }
            }
            if (!empty($this->datas['categories'])) {
                $sql .= 'AND c.`id_category` IN ('.implode(',', $this->datas['categories']).') '.PHP_EOL;
            }
            if (!empty($this->datas['manufacturers'])) {
                $sql .= 'AND p.`id_manufacturer` IN ('.implode(',', $this->datas['manufacturers']).') '.PHP_EOL;
            }
            if (!empty($this->datas['suppliers'])) {
                $sql .= 'AND p.`id_supplier` IN ('.implode(',', $this->datas['suppliers']).') '.PHP_EOL;
            }
            if ($this->datas['new'] && $this->datas['last_run']) {
                $sql .= 'AND p.`date_add` > \''.date('Y-m-d H:i:s', $this->datas['last_run']).'\' '.PHP_EOL;
            }
            if ($this->datas['with_ean'] == 0) {
                $sql .= 'AND p.`ean13` IN (\'0\', \'\') ';
            }
            if ($this->datas['with_ean'] == 2) {
                $sql .= 'AND p.`ean13` NOT IN (\'0\', \'\') ';
            }
            $sql .= ' GROUP BY  p.`id_product` ';
            if ($use_shop) {
                if ($this->datas['no_stock'] == 0) {
                    $sql .= ' HAVING `quantity` > 0 ';
                }
                if ($this->datas['no_stock'] == 2) {
                    $sql .= ' HAVING `quantity` = 0 ';
                }
            }

            $products2 = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            $result += count($products2);
            $products = array_merge($products, $products2);
        }
        $this->saveProductList($products);

        return $result;
    }

    public function saveProductList($products)
    {
        $filename = $this->directory.$this->filename.'.list';
        return file_put_contents($filename, $this->module->samdha_tools->jsonEncode($products));
    }

    public function getProductList()
    {
        $result = array();
        $filename = $this->directory.$this->filename.'.list';
        if (file_exists($filename)) {
            $content = $this->module->samdha_tools->fileGetContents($filename);
            $result = $this->module->samdha_tools->jsonDecode($content, true);
        }
        return $result;
    }

    public function deleteProductList()
    {
        $filename = $this->directory.$this->filename.'.list';
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    /**
     * return products attributes
     * may return more row than $limit if combinations are exported
     * may return less row than $limit if filter by category is activated
     *
     * @param  integer $start      first product to return
     * @param  integer $limit      how many products to return
     * @param  integer $nb_product say how many products (without combinations) are returned
     * @return array              the products properties
     */
    public function getProducts($limit = 0)
    {
        $result = array();
        $id_lang = $this->datas['id_lang'];

        $cart = $this->module->context->cart;
        if (!isset($cart)) {
            $cart = new Cart();
        }

        $list = $this->getProductList();
        $products = array_slice($list, 0, $limit);
        if (count($list) <= $limit) {
            $this->deleteProductList();
        } else {
            $this->saveProductList(array_slice($list, $limit));
        }

        $id_products = array();
        $id_products_attributes = array();
        foreach ($products as $product) {
            $id_products[$product['id_product']] = $product['id_product'];
            if (isset($product['id_product_attribute'])) {
                $id_products_attributes[$product['id_product_attribute']] = $product['id_product_attribute'];
            }
        }

        if (!empty($id_products)) {
            if (version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
                $days = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
                if (property_exists('Product', 'id_tax_rules_group')) {
                    $sql = '
                        SELECT p.*, pl.*, tax.`rate` tax_rate, i.`id_image`,
                            il.`legend`, m.`name` manufacturer_name,
                            s.`name` supplier_name, tax_lang.`name` tax_name,
                            DATEDIFF(
                                p.`date_add`,
                                DATE_SUB(
                                    NOW(),
                                    INTERVAL '.(Validate::isUnsignedInt($days) ? $days : 20).' DAY
                                )
                            ) > 0 new
                        FROM '._DB_PREFIX_.'product p
                        INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (
                            p.`id_product` = pl.`id_product`
                            AND pl.`id_lang` = '.(int) $id_lang.'
                        )
                        LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (
                            p.`id_tax_rules_group` = tr.`id_tax_rules_group`
                            AND tr.`id_country` = '.(int) Country::getDefaultCountryId().'
                            AND tr.`id_state` = 0
                        )
                        LEFT JOIN `'._DB_PREFIX_.'tax` tax ON (tax.`id_tax` = tr.`id_tax`)
                        LEFT JOIN `'._DB_PREFIX_.'tax_lang` tax_lang ON (
                            tax.`id_tax` = tax_lang.`id_tax`
                            AND tax_lang.`id_lang` = '.(int) $id_lang.'
                        )
                        LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                        LEFT JOIN `'._DB_PREFIX_.'supplier` s ON s.`id_supplier` = p.`id_supplier`
                        LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
                        LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (
                            i.`id_image` = il.`id_image`
                            AND il.`id_lang` = '.(int) $id_lang.'
                        )
                        WHERE p.`id_product` IN ('.implode(',', $id_products).')
                    ';
                } else {
                    $sql = '
                        SELECT p.*, pl.`description`, pl.`description_short`,
                            pl.`available_now`, pl.`available_later`,
                            pl.`link_rewrite`, pl.`name`, pl.meta_description,
                            pl.meta_keywords, pl.meta_title, tax.`rate` rate,
                            tax.`rate` tax_rate, i.`id_image`, il.`legend`,
                            m.`name` manufacturer_name, s.`name` supplier_name,
                            tax_lang.`name` tax_name,
                            DATEDIFF(
                                p.`date_add`,
                                DATE_SUB(
                                    NOW(),
                                    INTERVAL '.(Validate::isUnsignedInt($days) ? $days : 20).' DAY
                                )
                            ) > 0 new
                        FROM '._DB_PREFIX_.'product p
                        INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (
                            p.`id_product` = pl.`id_product`
                            AND pl.`id_lang` = '.(int) $id_lang.'
                        )
                        LEFT JOIN `'._DB_PREFIX_.'tax` tax ON (tax.`id_tax` = p.`id_tax`)
                        LEFT JOIN `'._DB_PREFIX_.'tax_lang` tax_lang ON (
                            tax.`id_tax` = tax_lang.`id_tax`
                            AND tax_lang.`id_lang` = '.(int) $id_lang.'
                        )
                        LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                        LEFT JOIN `'._DB_PREFIX_.'supplier` s ON s.`id_supplier` = p.`id_supplier`
                        LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
                        LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (
                            i.`id_image` = il.`id_image`
                            AND il.`id_lang` = '.(int) $id_lang.'
                        )
                        WHERE p.`id_product` IN ('.implode(',', $id_products).')
                    ';
                }
            } else {
                $sql = '
                    SELECT p.*, product_shop.*, pl.* , m.`name` AS manufacturer_name, s.`name` AS supplier_name
                    FROM `'._DB_PREFIX_.'product` p
                    '.Shop::addSqlAssociation('product', 'p').'
                    LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
                        p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').'
                    )
                    LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
                    LEFT JOIN `'._DB_PREFIX_.'supplier` s ON (s.`id_supplier` = p.`id_supplier`)
                    WHERE pl.`id_lang` = '.(int)$id_lang.'
                    AND p.`id_product` IN ('.implode(',', $id_products).')
                ';
            }
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
            if (version_compare(_PS_VERSION_, '1.5.3.0', '>=')) {
                foreach ($result as &$row) {
                    $row = Product::getTaxesInformations($row);
                }
            }
        }

        if ($result) {
            foreach ($result as $key => $value) {
                // price & weight may be modified in Samdha_ExportCatalog_Tools::getAttributeCombinations
                $result[$key]['product_price'] = $result[$key]['price'];
                $result[$key]['product_weight'] = $result[$key]['weight'];
            }
            // add optional fields

            if ($this->hasField('image_url')) {
                foreach ($result as $key => $value) {
                    $cover = Product::getCover($value['id_product']);
                    if (!is_array($cover)
                        || empty($cover)
                    ) {
                        $cover = array('id_image' => 0);
                    }
                    $result[$key] = array_merge($value, $cover);
                }
            }
            if ($this->hasField('image_url')
                || $this->hasField('image_url_0')
                || $this->hasField('image_url_1')
                || $this->hasField('image_url_2')
                || $this->hasField('image_url_3')
                || $this->hasField('image_url_4')
                || $this->hasField('image_url_5')
                || $this->hasField('image_url_6')
                || $this->hasField('image_url_7')
                || $this->hasField('image_url_8')
                || $this->hasField('image_url_9')
                || $this->hasField('image_url_all')
                || $this->hasField('legend')
                || $this->hasField('legend_0')
                || $this->hasField('legend_1')
                || $this->hasField('legend_2')
                || $this->hasField('legend_3')
                || $this->hasField('legend_4')
                || $this->hasField('legend_5')
                || $this->hasField('legend_6')
                || $this->hasField('legend_7')
                || $this->hasField('legend_8')
                || $this->hasField('legend_9')
                || $this->hasField('legend_all')
                || $this->hasField('image_position_all')
            ) {
                foreach ($result as $key => $value) {
                    $images = $this->module->tools->getProductImages($value['id_product'], $id_lang);
                    $result[$key]['images_count'] = count($images);
                    $result[$key]['product_images_count'] = $result[$key]['images_count'];
                    foreach ($images as $index => $image) {
                        $result[$key]['id_image_'.$index] = $image['id_image'];
                        $result[$key]['legend_'.$index] = $image['legend'];
                    }
                }
            }
            if ($this->hasField('category_0')
                || $this->hasField('category_1')
                || $this->hasField('category_2')
                || $this->hasField('category_3')
                || $this->hasField('category_4')
                || $this->hasField('category_5')
                || $this->hasField('category_6')
                || $this->hasField('category_7')
                || $this->hasField('category_8')
                || $this->hasField('category_9')
                || $this->hasField('category_all')
                || $this->hasField('category_name_0')
                || $this->hasField('category_name_1')
                || $this->hasField('category_name_2')
                || $this->hasField('category_name_3')
                || $this->hasField('category_name_4')
                || $this->hasField('category_name_5')
                || $this->hasField('category_name_6')
                || $this->hasField('category_name_7')
                || $this->hasField('category_name_8')
                || $this->hasField('category_name_9')
                || $this->hasField('category_name_all')
                || $this->hasField('id_category_0')
                || $this->hasField('id_category_1')
                || $this->hasField('id_category_2')
                || $this->hasField('id_category_3')
                || $this->hasField('id_category_4')
                || $this->hasField('id_category_5')
                || $this->hasField('id_category_6')
                || $this->hasField('id_category_7')
                || $this->hasField('id_category_8')
                || $this->hasField('id_category_9')
                || $this->hasField('id_category_all')
            ) {
                foreach ($result as $key => $value) {
                    $tmp = $this->module->tools->getProductCategoriesFull($value['id_product'], $id_lang);
                    $result[$key] = array_merge($result[$key], $tmp);
                }
            }

            if ($this->hasField('tag_0')
                || $this->hasField('tag_1')
                || $this->hasField('tag_2')
                || $this->hasField('tag_3')
                || $this->hasField('tag_4')
                || $this->hasField('tag_5')
                || $this->hasField('tag_6')
                || $this->hasField('tag_7')
                || $this->hasField('tag_8')
                || $this->hasField('tag_9')
                || $this->hasField('tag_all')
            ) {
                foreach ($result as $key => $value) {
                    $tags = $this->module->tools->getProductTags($value['id_product'], $id_lang);
                    if (is_array($tags)) {
                        foreach ($tags as $index => $tag) {
                            $result[$key]['tag_'.$index] = $tag;
                        }
                    }
                }
            }
            if ($this->hasField('full_name')) {
                foreach ($result as $key => $value) {
                    $result[$key]['full_name'] = $result[$key]['name'];
                }
            }

            if ($this->hasField('product_reference')) {
                foreach ($result as $key => $value) {
                    $result[$key]['product_reference'] = $result[$key]['reference'];
                }
            }

            if ($this->hasField('category_name')) {
                $result = $this->module->tools->getCategoriesNames($id_lang, $result);
            }

            if ($this->hasField('path')
                || $this->hasField('parent_category_0')
                || $this->hasField('parent_category_1')
                || $this->hasField('parent_category_2')
                || $this->hasField('parent_category_3')
                || $this->hasField('parent_category_4')
                || $this->hasField('parent_category_5')
                || $this->hasField('parent_category_6')
                || $this->hasField('parent_category_7')
                || $this->hasField('parent_category_8')
                || $this->hasField('parent_category_9')
                || $this->hasField('parent_category_all')
                || $this->hasField('parent_category_name_0')
                || $this->hasField('parent_category_name_1')
                || $this->hasField('parent_category_name_2')
                || $this->hasField('parent_category_name_3')
                || $this->hasField('parent_category_name_4')
                || $this->hasField('parent_category_name_5')
                || $this->hasField('parent_category_name_6')
                || $this->hasField('parent_category_name_7')
                || $this->hasField('parent_category_name_8')
                || $this->hasField('parent_category_name_9')
                || $this->hasField('parent_category_name_all')
                || $this->hasField('id_parent_category_0')
                || $this->hasField('id_parent_category_1')
                || $this->hasField('id_parent_category_2')
                || $this->hasField('id_parent_category_3')
                || $this->hasField('id_parent_category_4')
                || $this->hasField('id_parent_category_5')
                || $this->hasField('id_parent_category_6')
                || $this->hasField('id_parent_category_7')
                || $this->hasField('id_parent_category_8')
                || $this->hasField('id_parent_category_9')
                || $this->hasField('id_parent_category_all')
            ) {
                $result = $this->module->tools->getProductPath($result);
            }

            if ($this->datas['attribute']) {
                $result = $this->module->tools->getAttributeCombinations($id_lang, $result, $id_products_attributes);

                // on PS 1.5 $product['quantity'] may be wrong before Product::getProductsProperties
                if ($this->datas['no_stock'] == 0 && version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
                    foreach ($result as $key => $product) {
                        if ($product['quantity'] <= 0) {
                            unset($result[$key]);
                        }
                    }
                }

                $quantities = array();
                // on PS 1.3 attribute quantities will be overided by Product::getProductsProperties;
                if (version_compare(_PS_VERSION_, '1.4.0.0', '<')) {
                    foreach ($result as $key => $product) {
                        $quantities[$key] = $product['quantity'];
                    }
                }
            } else {
                // on PS 1.5 $product['quantity'] may be wrong before Product::getProductsProperties
                if ($this->datas['no_stock'] == 0 && version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
                    foreach ($result as $key => $product) {
                        if ($product['quantity'] <= 0) {
                            unset($result[$key]);
                        }
                    }
                }
                if ($this->datas['no_stock'] == 2 && version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
                    foreach ($result as $key => $product) {
                        if ($product['quantity'] > 0) {
                            unset($result[$key]);
                        }
                    }
                }
            }

            foreach ($result as $key => $product) {
                if (!isset($product['id_image'])) {
                    $result[$key]['id_image'] = 0; // avoid notice on Product::defineProductImage on Prestashop 1.4.4.1
                }
            }

            $result = Product::getProductsProperties($id_lang, $result);
            // on PS 1.3 attribute quantities have been overided by Product::getProductsProperties;
            if ($this->datas['attribute'] && version_compare(_PS_VERSION_, '1.4.0.0', '<')) {
                foreach ($result as $key => $product) {
                    $result[$key]['quantity'] = $quantities[$key];
                }
            }

            // specific prices for PS 1.4
            if (version_compare(_PS_VERSION_, '1.4.0.0', '>=')
                && ($this->hasField('reduction_price')
                    || $this->hasField('reduction_percent')
                    || $this->hasField('reduction_from')
                    || $this->hasField('reduction_to')
                )
            ) {
                foreach ($result as $key => $product) {
                    if (isset($product['specific_prices'])
                        && is_array($product['specific_prices'])
                        && !empty($product['specific_prices'])
                    ) {
                        $specific_price = $product['specific_prices'];
                        if ($specific_price['reduction_type'] == 'amount') {
                            $result[$key]['reduction_price'] = $specific_price['reduction'];
                            $result[$key]['reduction_percent'] = 0;
                        } else {
                            $result[$key]['reduction_price'] = 0;
                            $result[$key]['reduction_percent'] = $specific_price['reduction'];
                        }
                        $result[$key]['reduction_from'] = $specific_price['from'];
                        $result[$key]['reduction_to'] = $specific_price['to'];
                    }
                }
            }

            if (version_compare(_PS_VERSION_, '1.5.3.0', '>=')
                && (
                    $this->hasField('supplier_reference')
                    || $this->hasField('supplier_reference_1')
                    || $this->hasField('supplier_reference_2')
                    || $this->hasField('supplier_reference_3')
                    || $this->hasField('supplier_reference_4')
                    || $this->hasField('supplier_reference_5')
                    || $this->hasField('supplier_reference_6')
                    || $this->hasField('supplier_reference_7')
                    || $this->hasField('supplier_reference_8')
                    || $this->hasField('supplier_reference_9')
                    || $this->hasField('supplier_reference_all')
                    || $this->hasField('supplier_name_0')
                    || $this->hasField('supplier_name_1')
                    || $this->hasField('supplier_name_2')
                    || $this->hasField('supplier_name_3')
                    || $this->hasField('supplier_name_4')
                    || $this->hasField('supplier_name_5')
                    || $this->hasField('supplier_name_6')
                    || $this->hasField('supplier_name_7')
                    || $this->hasField('supplier_name_8')
                    || $this->hasField('supplier_name_9')
                    || $this->hasField('supplier_name_all')
                    || $this->hasField('id_supplier_0')
                    || $this->hasField('id_supplier_1')
                    || $this->hasField('id_supplier_2')
                    || $this->hasField('id_supplier_3')
                    || $this->hasField('id_supplier_4')
                    || $this->hasField('id_supplier_5')
                    || $this->hasField('id_supplier_6')
                    || $this->hasField('id_supplier_7')
                    || $this->hasField('id_supplier_8')
                    || $this->hasField('id_supplier_9')
                    || $this->hasField('id_supplier_all')
                )
            ) {
                $this->module->tools->cacheProductsSuppliers($id_products);
                foreach ($result as $key => $value) {
                    $tmp = $this->module->tools->getProductSuppliers(
                        $value['id_product'],
                        isset($value['id_product_attribute'])?$value['id_product_attribute']:0,
                        $value['id_supplier']
                    );
                    $result[$key] = array_merge($result[$key], $tmp);
                }
            }

            // filter products out of stock
            if ($this->datas['no_stock'] == 0) {
                foreach ($result as $key => $product) {
                    if ($product['quantity'] <= 0) {
                        unset($result[$key]);
                    }
                }
            }
            if ($this->datas['no_stock'] == 2) {
                foreach ($result as $key => $product) {
                    if ($product['quantity'] > 0) {
                        unset($result[$key]);
                    }
                }
            }

            // filter products without ean
            if ($this->datas['with_ean'] == 0) {
                foreach ($result as $key => $product) {
                    if ($product['ean13']) {
                        unset($result[$key]);
                    }
                }
            }
            if ($this->datas['with_ean'] == 2) {
                foreach ($result as $key => $product) {
                    if (!$product['ean13']) {
                        unset($result[$key]);
                    }
                }
            }

            // on PS 1.5 combination links may differ than product links
            if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')
                && ($this->hasField('link')
                    || $this->hasField('product_link')
                    || $this->hasField('combination_link')
                )
            ) {
                $context = Context::getContext();
                $force_routes = (bool) Configuration::get('PS_REWRITING_SETTINGS', null, null, $this->datas['id_shop']);
                foreach ($result as $key => $row) {
                    $result[$key]['link'] = $context->link->getProductLink(
                        $row['id_product'],
                        $row['link_rewrite'],
                        $row['category'],
                        $row['ean13'],
                        $id_lang,
                        $this->datas['id_shop'],
                        isset($row['id_product_attribute'])?$row['id_product_attribute']:0,
                        $force_routes
                    );

                    // fix the url anchor https://github.com/PrestaShop/PrestaShop/pull/637
                    // the anchor was duplicated
                    if (isset($row['id_product_attribute'])
                        && $row['id_product_attribute']
                        && version_compare(_PS_VERSION_, '1.5.4.1', '<=')) {
                        $url = explode('#', $result[$key]['link']);
                        if (isset($url[1])) {
                            $anchor_length = Tools::strlen($url[1]);
                            if (!($anchor_length & 1)
                                && Tools::substr($url[1], $anchor_length / 2)
                                == Tools::substr($url[1], 0, $anchor_length / 2)) {
                                $result[$key]['link'] = $url[0].'#'.Tools::substr($url[1], 0, $anchor_length / 2);
                            }
                        }
                    }
                }
            }

            if ($this->hasField('product_link')) {
                foreach ($result as $key => $row) {
                    if ($row['id_product_attribute']) {
                        $product_link = explode('#', $row['link']);
                        $result[$key]['product_link'] = $product_link[0];
                    } else {
                        $result[$key]['product_link'] = $row['link'];
                    }
                }
            }

            if ($this->hasField('combination_link')) {
                foreach ($result as $key => $row) {
                    if ($row['id_product_attribute']) {
                        $result[$key]['combination_link'] = $row['link'];
                    } else {
                        $result[$key]['combination_link'] = '';
                    }
                }
            }
        }
        return $result;
    }

    /**
     * write column names in output file if needed
     *
     * @param  handler $out output file handler
     * @return void
     */
    public function writeHeader($out)
    {
        if ($this->datas['header']) {
            $line = array();
            foreach ($this->datas['fields'] as $field) {
                $line[] = $field['title'];
            }

            if ($this->datas['charset'] !== 'UTF-8') {
                foreach ($line as &$value) {
                    $value = $this->module->tools->iconv('UTF-8', $this->datas['charset'], $value);
                }
            }

            $this->module->tools->fPutCSV(
                $out,
                $line,
                $this->datas['separator'],
                $this->datas['enclosure'],
                $this->datas['force_enclosure']
            );
        }
    }


    /**
     * write products lines in output file
     *
     * @param  handler $out output file handler
     * @param  array $products products properties @see getProducts
     * @param  string $host current shop host
     * @return void
     */
    public function writeProducts($out, $products, $host)
    {
        static $shipping_cache = array(), $shipping_tax_exc_cache = array();

        foreach ($products as $product) {
            $line = array();
            $i = 0;
            foreach ($this->datas['fields'] as $field) {
                switch ($field['id']) {
                    case 'fix':
                        $line[$i] = $field['value'];
                        break;
                    case 'full_product_id':
                        $line[$i] = $product['id_product'].'-'
                            .(isset($product['id_product_attribute'])?$product['id_product_attribute']:'0');
                        break;
                    case 'image_url':
                        $line[$i] = $host.$this->module->tools->getImageLink(
                            $product['link_rewrite'],
                            $product['id_image'],
                            $this->datas['size'],
                            $this->datas['id_shop']
                        );
                        break;
                    case 'image_url_0':
                    case 'image_url_1':
                    case 'image_url_2':
                    case 'image_url_3':
                    case 'image_url_4':
                    case 'image_url_5':
                    case 'image_url_6':
                    case 'image_url_7':
                    case 'image_url_8':
                    case 'image_url_9':
                        $image_number = Tools::substr($field['id'], 10);
                        if (isset($product['id_image_'.$image_number])) {
                            $line[$i] = $host.$this->module->tools->getImageLink(
                                $product['link_rewrite'],
                                $product['id_image_'.$image_number],
                                $this->datas['size'],
                                $this->datas['id_shop']
                            );
                        } else {
                            $line[$i] = '';
                        }
                        break;
                    case 'image_url_all':
                        $images = array();
                        for ($image_number = 0; $image_number < 10; $image_number++) {
                            if (isset($product['id_image_'.$image_number])) {
                                $images[] = $host.$this->module->tools->getImageLink(
                                    $product['link_rewrite'],
                                    $product['id_image_'.$image_number],
                                    $this->datas['size'],
                                    $this->datas['id_shop']
                                );
                            }
                        }
                        $line[$i] = implode($field['value'], $images);
                        break;
                    case 'legend_all':
                        $legends = array();
                        for ($legend_number = 0; $legend_number < 10; $legend_number++) {
                            if (isset($product['legend_'.$legend_number])) {
                                $legends[] = $product['legend_'.$legend_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $legends);
                        break;
                    case 'category_all':
                        $names = array();
                        for ($name_number = 0; $name_number < 10; $name_number++) {
                            if (isset($product['category_'.$name_number])) {
                                $names[] = $product['category_'.$name_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $names);
                        break;
                    case 'category_name_all':
                        $names = array();
                        for ($name_number = 0; $name_number < 10; $name_number++) {
                            if (isset($product['category_name_'.$name_number])) {
                                $names[] = $product['category_name_'.$name_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $names);
                        break;
                    case 'id_category_all':
                        $ids = array();
                        for ($id_number = 0; $id_number < 10; $id_number++) {
                            if (isset($product['id_category_'.$id_number])) {
                                $ids[] = $product['id_category_'.$id_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $ids);
                        break;
                    case 'parent_category_all':
                        $names = array();
                        for ($name_number = 0; $name_number < 10; $name_number++) {
                            if (isset($product['parent_category_'.$name_number])) {
                                $names[] = $product['parent_category_'.$name_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $names);
                        break;
                    case 'parent_category_name_all':
                        $names = array();
                        for ($name_number = 0; $name_number < 10; $name_number++) {
                            if (isset($product['parent_category_name_'.$name_number])) {
                                $names[] = $product['parent_category_name_'.$name_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $names);
                        break;
                    case 'id_parent_category_all':
                        $ids = array();
                        for ($id_number = 0; $id_number < 10; $id_number++) {
                            if (isset($product['id_parent_category_'.$id_number])) {
                                $ids[] = $product['id_parent_category_'.$id_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $ids);
                        break;
                    case 'tag_all':
                        $tags = array();
                        for ($id_number = 0; $id_number < 10; $id_number++) {
                            if (isset($product['tag_'.$id_number])) {
                                $tags[] = $product['tag_'.$id_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $tags);
                        break;
                    case 'id_supplier_all':
                        $ids = array();
                        for ($id_number = 0; $id_number < 10; $id_number++) {
                            if (isset($product['id_supplier_'.$id_number])) {
                                $ids[] = $product['id_supplier_'.$id_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $ids);
                        break;
                    case 'supplier_name_all':
                        $ids = array();
                        for ($id_number = 0; $id_number < 10; $id_number++) {
                            if (isset($product['supplier_name_'.$id_number])) {
                                $ids[] = $product['supplier_name_'.$id_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $ids);
                        break;
                    case 'supplier_reference_all':
                        $ids = array();
                        for ($id_number = 0; $id_number < 10; $id_number++) {
                            if (isset($product['supplier_reference_'.$id_number])) {
                                $ids[] = $product['supplier_reference_'.$id_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $ids);
                        break;
                    case 'image_position_all':
                        $ids = array();
                        for ($id_number = 0; $id_number < 10; $id_number++) {
                            if (isset($product['image_position_'.$id_number])) {
                                $ids[] = $product['image_position_'.$id_number];
                            }
                        }
                        $line[$i] = implode($field['value'], $ids);
                        break;
                    case 'attribute_all':
                        $ids = array();
                        for ($id_number = 0; $id_number < 10; $id_number++) {
                            if (isset($product['attribute_'.$id_number])) {
                                $ids[] = $product['attribute_'.$id_number]
                                    .':'.$product['attribute_type_'.$id_number]
                                    .':'.$id_number;
                            }
                        }
                        $line[$i] = implode($field['value'], $ids);
                        break;
                    case 'attribute_value_all':
                        $ids = array();
                        for ($id_number = 0; $id_number < 10; $id_number++) {
                            if (isset($product['attribute_value_'.$id_number])) {
                                $ids[] = $product['attribute_value_'.$id_number].':'.$id_number;
                            }
                        }
                        $line[$i] = implode($field['value'], $ids);
                        break;
                    case 'description':
                        $line[$i] = str_replace(array("\r\n","\n","\r"), ' ', $product['description']);
                        break;
                    case 'description_short':
                        $line[$i] = str_replace(array("\r\n","\n","\r"), ' ', $product['description_short']);
                        break;
                    case 'description_clean':
                        $line[$i] = str_replace(
                            array("\r\n","\n","\r"),
                            ' ',
                            trim(strip_tags($product['description']))
                        );
                        break;
                    case 'description_short_clean':
                        $line[$i] = str_replace(
                            array("\r\n","\n","\r"),
                            ' ',
                            trim(strip_tags($product['description_short']))
                        );
                        break;
                    case 'in_stock':
                        $line[$i] = $product['quantity'] > 0?1:0;
                        break;
                    case 'in_stock_text':
                        if ($product['quantity'] > 0) {
                            $line[$i] = $this->module->l('In stock', 'model', $this->datas['id_lang']);
                        } else {
                            $line[$i] = $this->module->l('Out of stock', 'model', $this->datas['id_lang']);
                        }
                        break;
                    case 'condition':
                        $line[$i] = $this->module->l($product['condition'], 'model', $this->datas['id_lang']);
                        //$this->module->l('new', 'model', $id_lang),
                        //$this->module->l('used', 'model', $id_lang),
                        //$this->module->l('refurbished', 'model', $id_lang),
                        break;
                    case 'features':
                        $line[$i] = $this->module->tools->formatProductFeatures($product);
                        break;
                    case 'shipping':
                        $key = $product['id_product'].'-'.$product['price'].'-'.$product['weight'];
                        if (isset($shipping_cache[$key])) {
                            $shipping = $shipping_cache[$key];
                        } else {
                            if (isset($this->datas['simple_shipping'])
                                && $this->datas['simple_shipping']) {
                                $shipping = $this->module->tools->getProductShippingCostSimple(
                                    $product['price'],
                                    $product['weight'],
                                    true,
                                    $this->datas['id_currency'],
                                    $this->datas['id_country'],
                                    $this->datas['id_group'],
                                    /*$this->datas['id_shop'],*/
                                    isset($product['additional_shipping_cost'])?$product['additional_shipping_cost']:0
                                );
                            } else {
                                $shipping = $this->module->tools->getProductShippingCost(
                                    $product['id_product'],
                                    $product['id_product_attribute'],
                                    true,
                                    $this->datas['id_currency'],
                                    $this->datas['id_country'],
                                    $this->datas['id_group'],
                                    $this->datas['id_shop'],
                                    $this->datas['id_lang']
                                );
                            }
                            $shipping_cache[$key] = $shipping;
                        }
                        $line[$i] = $this->formatPrice($shipping);
                        break;
                    case 'shipping_tax_exc':
                        $key = $product['id_product'].'-'.$product['price'].'-'.$product['weight'];
                        if (isset($shipping_tax_exc_cache[$key])) {
                            $shipping = $shipping_tax_exc_cache[$key];
                        } else {
                            if (isset($this->datas['simple_shipping'])
                                && $this->datas['simple_shipping']) {
                                $shipping = $this->module->tools->getProductShippingCostSimple(
                                    $product['price'],
                                    $product['weight'],
                                    false,
                                    $this->datas['id_currency'],
                                    $this->datas['id_country'],
                                    $this->datas['id_group'],
                                    /*$this->datas['id_shop'],*/
                                    isset($product['additional_shipping_cost'])?$product['additional_shipping_cost']:0
                                );
                            } else {
                                $shipping = $this->module->tools->getProductShippingCost(
                                    $product['id_product'],
                                    $product['id_product_attribute'],
                                    false,
                                    $this->datas['id_currency'],
                                    $this->datas['id_country'],
                                    $this->datas['id_group'],
                                    $this->datas['id_shop'],
                                    $this->datas['id_lang']
                                );
                            }
                            $shipping_tax_exc_cache[$key] = $shipping;
                        }
                        $line[$i] = $this->formatPrice($shipping);
                        break;
                    case 'price':
                    case 'rate':
                    case 'tax_rate':
                    case 'price_tax_exc':
                    case 'price_without_reduction':
                    case 'reduction':
                    case 'ecotax':
                    case 'additional_shipping_cost':
                    case 'unit_price_ratio':
                    case 'wholesale_price':
                    case 'product_price':
                    case 'combination_price':
                    case 'unit_price_impact':
                        $line[$i] = isset($product[$field['id']])?$this->formatPrice($product[$field['id']]):'';
                        break;
                    case 'width':
                    case 'height':
                    case 'depth':
                    case 'weight':
                    case 'product_weight':
                    case 'combination_weight':
                        $line[$i] = isset($product[$field['id']])?$this->formatNumber($product[$field['id']]):'';
                        break;
                    default:
                        if (Tools::substr($field['id'], 0, 8) == 'feature_') {
                            $line[$i] = $this->module->tools->getProductFeatureValue(
                                $product['id_product'],
                                Tools::substr($field['id'], 8),
                                $this->datas['id_lang']
                            );
                        } else {
                            $line[$i] = isset($product[$field['id']])?$product[$field['id']]:'';
                        }
                }
                if (isset($this->datas['decoration']) && $this->datas['decoration']) {
                    if (isset($field['before'])) {
                        $line[$i] = $field['before'].$line[$i];
                    }
                    if (isset($field['after'])) {
                        $line[$i] .= $field['after'];
                    }
                }
                $i++;
            }
            foreach ($line as &$value) {
                if ($value && !is_numeric($value)) {
                    $value = $this->module->tools->iconv('UTF-8', $this->datas['charset'], $value);
                }
            }
            unset($value);
            $this->module->tools->fPutCSV(
                $out,
                $line,
                $this->datas['separator'],
                $this->datas['enclosure'],
                $this->datas['force_enclosure']
            );
        }
    }

    public function formatPrice($number)
    {
        $result = number_format($number, $this->datas['precision'], $this->datas['decimal'], '');
        if ($this->datas['price_format'] != '') {
            $result = str_replace('[PRICE]', $result, $this->datas['price_format']);
        }
        return $result;
    }

    public function formatNumber($number)
    {
        return str_replace('.', $this->datas['decimal'], $number);
    }

    public function export($filename)
    {
        if (file_exists($filename)) {
            $this->datas['last_run'] = time();
            $this->save();
            header('Content-Description: File Transfer');
            header('Content-Type: text/csv');
            header('Content-disposition: attachment; filename='.strftime($this->datas['filename']));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '.filesize($filename));
            $this->module->tools->readFileChunked($filename);
            unlink($filename);
        } else {
            header('HTTP/1.1 404 Not Found');
            header('Status: 404 Not Found');
        }
    }

    /**
     * get fields exportable for current prestashop installation
     * array(
     *     field key => field name
     * )
     * if $id_lang is false $this->datas['id_lang'] is used
     *
     * @param  boolean/integer $id_lang language of fields names
     * @return array
     */
    public function getPossibleFields($id_lang = false)
    {
        if (!$id_lang) {
            $id_lang = $this->datas['id_lang'];
        }

        $result = array(
            'name' => $this->module->l('Name', 'model', $id_lang),
            'combination_name' => $this->module->l('Combination name', 'model', $id_lang),
            'full_name' => $this->module->l('Name + Combination name', 'model', $id_lang),
            'description_short' => $this->module->l('Description short', 'model', $id_lang),
            'description_short_clean' => $this->module->l('Description short without HTML', 'model', $id_lang),
            'description' => $this->module->l('Description', 'model', $id_lang),
            'description_clean' => $this->module->l('Description without HTML', 'model', $id_lang),

            'image_url' => $this->module->l('Default image URL', 'model', $id_lang),
            'image_url_0' => $this->module->l('Image 1 URL', 'model', $id_lang),
            'image_url_1' => $this->module->l('Image 2 URL', 'model', $id_lang),
            'image_url_2' => $this->module->l('Image 3 URL', 'model', $id_lang),
            'image_url_3' => $this->module->l('Image 4 URL', 'model', $id_lang),
            'image_url_4' => $this->module->l('Image 5 URL', 'model', $id_lang),
            'image_url_5' => $this->module->l('Image 6 URL', 'model', $id_lang),
            'image_url_6' => $this->module->l('Image 7 URL', 'model', $id_lang),
            'image_url_7' => $this->module->l('Image 8 URL', 'model', $id_lang),
            'image_url_8' => $this->module->l('Image 9 URL', 'model', $id_lang),
            'image_url_9' => $this->module->l('Image 10 URL', 'model', $id_lang),
            'image_url_all' => $this->module->l('All images URL', 'model', $id_lang),
            'legend' => $this->module->l('Default image legend', 'model', $id_lang),
            'legend_0' => $this->module->l('Image 1 legend', 'model', $id_lang),
            'legend_1' => $this->module->l('Image 2 legend', 'model', $id_lang),
            'legend_2' => $this->module->l('Image 3 legend', 'model', $id_lang),
            'legend_3' => $this->module->l('Image 4 legend', 'model', $id_lang),
            'legend_4' => $this->module->l('Image 5 legend', 'model', $id_lang),
            'legend_5' => $this->module->l('Image 6 legend', 'model', $id_lang),
            'legend_6' => $this->module->l('Image 7 legend', 'model', $id_lang),
            'legend_7' => $this->module->l('Image 8 legend', 'model', $id_lang),
            'legend_8' => $this->module->l('Image 9 legend', 'model', $id_lang),
            'legend_9' => $this->module->l('Image 10 legend', 'model', $id_lang),
            'legende_all' => $this->module->l('All images legends', 'model', $id_lang),
            'images_count' => $this->module->l('Number of images', 'model', $id_lang),
            'product_images_count' => $this->module->l('Product number of images', 'model', $id_lang),
            'combination_images_count' => $this->module->l('Combination number of images', 'model', $id_lang),

            'quantity' => $this->module->l('Quantity', 'model', $id_lang),
            'reference' => $this->module->l('Reference', 'model', $id_lang),
            'product_reference' => $this->module->l('Product reference', 'model', $id_lang),
            'combination_reference' => $this->module->l('Combination reference', 'model', $id_lang),

            'manufacturer_name' => $this->module->l('Manufacturer', 'model', $id_lang),
            'id_manufacturer' => $this->module->l('Manufacturer ID', 'model', $id_lang),
            'id_shop_default' => $this->module->l('ID default shop', 'model', $id_lang),
            'link_rewrite' => $this->module->l('Friendly URL', 'model', $id_lang),
            'tag_0' => $this->module->l('Tag 1', 'model', $id_lang),
            'tag_1' => $this->module->l('Tag 2', 'model', $id_lang),
            'tag_2' => $this->module->l('Tag 3', 'model', $id_lang),
            'tag_3' => $this->module->l('Tag 4', 'model', $id_lang),
            'tag_4' => $this->module->l('Tag 5', 'model', $id_lang),
            'tag_5' => $this->module->l('Tag 6', 'model', $id_lang),
            'tag_6' => $this->module->l('Tag 7', 'model', $id_lang),
            'tag_7' => $this->module->l('Tag 8', 'model', $id_lang),
            'tag_8' => $this->module->l('Tag 9', 'model', $id_lang),
            'tag_9' => $this->module->l('Tag 10', 'model', $id_lang),
            'tag_all' => $this->module->l('All tags', 'model', $id_lang),

            'supplier_reference' => $this->module->l('Supplier reference', 'model', $id_lang),
            'supplier_name' => $this->module->l('Supplier name', 'model', $id_lang),
            'id_supplier' => $this->module->l('Supplier ID', 'model', $id_lang),

            'supplier_reference_0' => $this->module->l('Supplier 1 reference', 'model', $id_lang),
            'supplier_name_0' => $this->module->l('Supplier 1 name', 'model', $id_lang),
            'id_supplier_0' => $this->module->l('Supplier 1 ID', 'model', $id_lang),
            'supplier_reference_1' => $this->module->l('Supplier 2 reference', 'model', $id_lang),
            'supplier_name_1' => $this->module->l('Supplier 2 name', 'model', $id_lang),
            'id_supplier_1' => $this->module->l('Supplier 2 ID', 'model', $id_lang),
            'supplier_reference_2' => $this->module->l('Supplier 3 reference', 'model', $id_lang),
            'supplier_name_2' => $this->module->l('Supplier 3 name', 'model', $id_lang),
            'id_supplier_2' => $this->module->l('Supplier 3 ID', 'model', $id_lang),
            'supplier_reference_3' => $this->module->l('Supplier 4 reference', 'model', $id_lang),
            'supplier_name_3' => $this->module->l('Supplier 4 name', 'model', $id_lang),
            'id_supplier_3' => $this->module->l('Supplier 4 ID', 'model', $id_lang),
            'supplier_reference_4' => $this->module->l('Supplier 5 reference', 'model', $id_lang),
            'supplier_name_4' => $this->module->l('Supplier 5 name', 'model', $id_lang),
            'id_supplier_4' => $this->module->l('Supplier 5 ID', 'model', $id_lang),
            'supplier_reference_5' => $this->module->l('Supplier 6 reference', 'model', $id_lang),
            'supplier_name_5' => $this->module->l('Supplier 6 name', 'model', $id_lang),
            'id_supplier_5' => $this->module->l('Supplier 6 ID', 'model', $id_lang),
            'supplier_reference_6' => $this->module->l('Supplier 7 reference', 'model', $id_lang),
            'supplier_name_6' => $this->module->l('Supplier 7 name', 'model', $id_lang),
            'id_supplier_6' => $this->module->l('Supplier 7 ID', 'model', $id_lang),
            'supplier_reference_7' => $this->module->l('Supplier 8 reference', 'model', $id_lang),
            'supplier_name_7' => $this->module->l('Supplier 8 name', 'model', $id_lang),
            'id_supplier_7' => $this->module->l('Supplier 8 ID', 'model', $id_lang),
            'supplier_reference_8' => $this->module->l('Supplier 9 reference', 'model', $id_lang),
            'supplier_name_8' => $this->module->l('Supplier 9 name', 'model', $id_lang),
            'id_supplier_8' => $this->module->l('Supplier 9 ID', 'model', $id_lang),
            'supplier_reference_9' => $this->module->l('Supplier 10 reference', 'model', $id_lang),
            'supplier_name_9' => $this->module->l('Supplier 10 name', 'model', $id_lang),
            'id_supplier_9' => $this->module->l('Supplier 10 ID', 'model', $id_lang),
            'supplier_reference_all' => $this->module->l('All suppliers reference', 'model', $id_lang),
            'supplier_name_all' => $this->module->l('All suppliers name', 'model', $id_lang),
            'id_supplier_all' => $this->module->l('All suppliers ID', 'model', $id_lang),

            'meta_description' => $this->module->l('Meta description', 'model', $id_lang),
            'meta_keywords' => $this->module->l('Meta keywords', 'model', $id_lang),
            'meta_title' => $this->module->l('Meta title', 'model', $id_lang),
            'available_now' => $this->module->l('Text when available now', 'model', $id_lang),
            'available_later' => $this->module->l('Text when available later', 'model', $id_lang),
            'features' => $this->module->l('Features', 'model', $id_lang),

            'id_product' => $this->module->l('Product ID', 'model', $id_lang),
            'id_product_attribute' => $this->module->l('Combination ID', 'model', $id_lang),
            'full_product_id' => $this->module->l('Product ID + Combination ID', 'model', $id_lang),
            'ean13' => $this->module->l('EAN13', 'model', $id_lang),
            'upc' => $this->module->l('UPC', 'model', $id_lang),

            'price' => $this->module->l('Retail price', 'model', $id_lang),
            'rate' => $this->module->l('Tax rate', 'model', $id_lang),
            'tax_rate' => $this->module->l('Tax rate', 'model', $id_lang),
            'id_tax' => $this->module->l('Tax ID', 'model', $id_lang),
            'id_tax_rules_group' => $this->module->l('Tax rules group ID', 'model', $id_lang),
            'tax_name' => $this->module->l('Tax name', 'model', $id_lang),
            'price_tax_exc' => $this->module->l('Pre-tax retail price', 'model', $id_lang),
            'price_without_reduction' => $this->module->l('Price without reduction', 'model', $id_lang),
            'reduction' => $this->module->l('Reduction', 'model', $id_lang),
            'ecotax' => $this->module->l('ecotax', 'model', $id_lang),
            'additional_shipping_cost' => $this->module->l('Additional shipping cost', 'model', $id_lang),
            'unit_price_ratio' => $this->module->l('Unit price ratio', 'model', $id_lang),
            'minimal_quantity' => $this->module->l('Minimal quantity', 'model', $id_lang),
            'wholesale_price' => $this->module->l('Pre-tax wholesale price', 'model', $id_lang),

            'shipping' => $this->module->l('Shipping cost', 'model', $id_lang),
            'shipping_tax_exc' => $this->module->l('Pre-tax shipping cost', 'model', $id_lang),

            'on_sale' => $this->module->l('On sale', 'model', $id_lang),
            'online_only' => $this->module->l('Online only', 'model', $id_lang),
            'new' => $this->module->l('New', 'model', $id_lang),
            'active' => $this->module->l('Active', 'model', $id_lang),
            'available_for_order' => $this->module->l('Available for order', 'model', $id_lang),
            'in_stock' => $this->module->l('In stock (1/0)', 'model', $id_lang),
            'in_stock_text' => $this->module->l('In stock (text)', 'model', $id_lang),
            'show_price' => $this->module->l('Show price', 'model', $id_lang),
            'visibility' => $this->module->l('Visibility', 'model', $id_lang),
            'out_of_stock' => $this->module->l('When out of stock', 'model', $id_lang),
            'advanced_stock_management' => $this->module->l('Advanced stock management', 'model', $id_lang),

            'location' => $this->module->l('Location', 'model', $id_lang),
            'width' => $this->module->l('Width', 'model', $id_lang),
            'height' => $this->module->l('Height', 'model', $id_lang),
            'depth' => $this->module->l('Depth', 'model', $id_lang),
            'weight' => $this->module->l('Weight', 'model', $id_lang),
            'unity' => $this->module->l('Unity', 'model', $id_lang),
            'condition' => $this->module->l('Condition', 'model', $id_lang),
            'virtual' => $this->module->l('Virtual', 'model', $id_lang),

            'id_category_default' => $this->module->l('Default category ID', 'model', $id_lang),
            'id_category_0' => $this->module->l('Category 1 ID', 'model', $id_lang),
            'id_category_1' => $this->module->l('Category 2 ID', 'model', $id_lang),
            'id_category_2' => $this->module->l('Category 3 ID', 'model', $id_lang),
            'id_category_3' => $this->module->l('Category 4 ID', 'model', $id_lang),
            'id_category_4' => $this->module->l('Category 5 ID', 'model', $id_lang),
            'id_category_5' => $this->module->l('Category 6 ID', 'model', $id_lang),
            'id_category_6' => $this->module->l('Category 7 ID', 'model', $id_lang),
            'id_category_7' => $this->module->l('Category 8 ID', 'model', $id_lang),
            'id_category_8' => $this->module->l('Category 9 ID', 'model', $id_lang),
            'id_category_9' => $this->module->l('Category 10 ID', 'model', $id_lang),
            'id_category_all' => $this->module->l('All categories ID', 'model', $id_lang),

            'category_name' => $this->module->l('Default category name', 'model', $id_lang),
            'category_name_0' => $this->module->l('Category 1 name', 'model', $id_lang),
            'category_name_1' => $this->module->l('Category 2 name', 'model', $id_lang),
            'category_name_2' => $this->module->l('Category 3 name', 'model', $id_lang),
            'category_name_3' => $this->module->l('Category 4 name', 'model', $id_lang),
            'category_name_4' => $this->module->l('Category 5 name', 'model', $id_lang),
            'category_name_5' => $this->module->l('Category 6 name', 'model', $id_lang),
            'category_name_6' => $this->module->l('Category 7 name', 'model', $id_lang),
            'category_name_7' => $this->module->l('Category 8 name', 'model', $id_lang),
            'category_name_8' => $this->module->l('Category 9 name', 'model', $id_lang),
            'category_name_9' => $this->module->l('Category 10 name', 'model', $id_lang),
            'category_name_all' => $this->module->l('All categories name', 'model', $id_lang),

            'category' => $this->module->l('Default category friendly URL', 'model', $id_lang),
            'category_0' => $this->module->l('Category 1 friendly URL', 'model', $id_lang),
            'category_1' => $this->module->l('Category 2 friendly URL', 'model', $id_lang),
            'category_2' => $this->module->l('Category 3 friendly URL', 'model', $id_lang),
            'category_3' => $this->module->l('Category 4 friendly URL', 'model', $id_lang),
            'category_4' => $this->module->l('Category 5 friendly URL', 'model', $id_lang),
            'category_5' => $this->module->l('Category 6 friendly URL', 'model', $id_lang),
            'category_6' => $this->module->l('Category 7 friendly URL', 'model', $id_lang),
            'category_7' => $this->module->l('Category 8 friendly URL', 'model', $id_lang),
            'category_8' => $this->module->l('Category 9 friendly URL', 'model', $id_lang),
            'category_9' => $this->module->l('Category 10 friendly URL', 'model', $id_lang),
            'category_all' => $this->module->l('All categories friendly URL', 'model', $id_lang),

            'id_parent_category_0' => $this->module->l('Parent category 1 ID', 'model', $id_lang),
            'id_parent_category_1' => $this->module->l('Parent category 2 ID', 'model', $id_lang),
            'id_parent_category_2' => $this->module->l('Parent category 3 ID', 'model', $id_lang),
            'id_parent_category_3' => $this->module->l('Parent category 4 ID', 'model', $id_lang),
            'id_parent_category_4' => $this->module->l('Parent category 5 ID', 'model', $id_lang),
            'id_parent_category_5' => $this->module->l('Parent category 6 ID', 'model', $id_lang),
            'id_parent_category_6' => $this->module->l('Parent category 7 ID', 'model', $id_lang),
            'id_parent_category_7' => $this->module->l('Parent category 8 ID', 'model', $id_lang),
            'id_parent_category_8' => $this->module->l('Parent category 9 ID', 'model', $id_lang),
            'id_parent_category_9' => $this->module->l('Parent category 10 ID', 'model', $id_lang),
            'id_parent_category_all' => $this->module->l('All parent categories ID', 'model', $id_lang),

            'parent_category_name_0' => $this->module->l('Parent category 1 name', 'model', $id_lang),
            'parent_category_name_1' => $this->module->l('Parent category 2 name', 'model', $id_lang),
            'parent_category_name_2' => $this->module->l('Parent category 3 name', 'model', $id_lang),
            'parent_category_name_3' => $this->module->l('Parent category 4 name', 'model', $id_lang),
            'parent_category_name_4' => $this->module->l('Parent category 5 name', 'model', $id_lang),
            'parent_category_name_5' => $this->module->l('Parent category 6 name', 'model', $id_lang),
            'parent_category_name_6' => $this->module->l('Parent category 7 name', 'model', $id_lang),
            'parent_category_name_7' => $this->module->l('Parent category 8 name', 'model', $id_lang),
            'parent_category_name_8' => $this->module->l('Parent category 9 name', 'model', $id_lang),
            'parent_category_name_9' => $this->module->l('Parent category 10 name', 'model', $id_lang),
            'parent_category_name_all' => $this->module->l('All parent categories name', 'model', $id_lang),

            'parent_category_0' => $this->module->l('Parent category 1 friendly URL', 'model', $id_lang),
            'parent_category_1' => $this->module->l('Parent category 2 friendly URL', 'model', $id_lang),
            'parent_category_2' => $this->module->l('Parent category 3 friendly URL', 'model', $id_lang),
            'parent_category_3' => $this->module->l('Parent category 4 friendly URL', 'model', $id_lang),
            'parent_category_4' => $this->module->l('Parent category 5 friendly URL', 'model', $id_lang),
            'parent_category_5' => $this->module->l('Parent category 6 friendly URL', 'model', $id_lang),
            'parent_category_6' => $this->module->l('Parent category 7 friendly URL', 'model', $id_lang),
            'parent_category_7' => $this->module->l('Parent category 8 friendly URL', 'model', $id_lang),
            'parent_category_8' => $this->module->l('Parent category 9 friendly URL', 'model', $id_lang),
            'parent_category_9' => $this->module->l('Parent category 10 friendly URL', 'model', $id_lang),
            'parent_category_all' => $this->module->l('All parent categories friendly URL', 'model', $id_lang),

            'attribute_0' => $this->module->l('Combination attribute 1 name', 'model', $id_lang),
            'attribute_value_0' => $this->module->l('Combination attribute 1 value', 'model', $id_lang),
            'attribute_1' => $this->module->l('Combination attribute 2 name', 'model', $id_lang),
            'attribute_value_1' => $this->module->l('Combination attribute 2 value', 'model', $id_lang),
            'attribute_2' => $this->module->l('Combination attribute 3 name', 'model', $id_lang),
            'attribute_value_2' => $this->module->l('Combination attribute 3 value', 'model', $id_lang),
            'attribute_3' => $this->module->l('Combination attribute 4 name', 'model', $id_lang),
            'attribute_value_3' => $this->module->l('Combination attribute 4 value', 'model', $id_lang),
            'attribute_4' => $this->module->l('Combination attribute 5 name', 'model', $id_lang),
            'attribute_value_4' => $this->module->l('Combination attribute 5 value', 'model', $id_lang),
            'attribute_5' => $this->module->l('Combination attribute 6 name', 'model', $id_lang),
            'attribute_value_5' => $this->module->l('Combination attribute 6 value', 'model', $id_lang),
            'attribute_6' => $this->module->l('Combination attribute 7 name', 'model', $id_lang),
            'attribute_value_6' => $this->module->l('Combination attribute 7 value', 'model', $id_lang),
            'attribute_7' => $this->module->l('Combination attribute 8 name', 'model', $id_lang),
            'attribute_value_7' => $this->module->l('Combination attribute 8 value', 'model', $id_lang),
            'attribute_8' => $this->module->l('Combination attribute 9 name', 'model', $id_lang),
            'attribute_value_8' => $this->module->l('Combination attribute 9 value', 'model', $id_lang),
            'attribute_9' => $this->module->l('Combination attribute 10 name', 'model', $id_lang),
            'attribute_value_9' => $this->module->l('Combination attribute 10 value', 'model', $id_lang),
            'attribute_all' => $this->module->l('Attribute (Name:Type:Position)', 'model', $id_lang),
            'attribute_value_all' => $this->module->l('Value (Value:Position)', 'model', $id_lang),

            'unit_price_impact' => $this->module->l('Combination unit price impact', 'model', $id_lang),
            'default_on' => $this->module->l('Default combination', 'model', $id_lang),
            'combination_price' => $this->module->l('Combination price impact', 'model', $id_lang),
            'product_price' => $this->module->l('Product price before combination impact', 'model', $id_lang),
            'combination_weight' => $this->module->l('Combination weight impact', 'model', $id_lang),
            'product_weight' => $this->module->l('Product weight before combination impact', 'model', $id_lang),
            'image_position_all' => $this->module->l('Combination images by positions', 'model', $id_lang),

            'reduction_price' => $this->module->l('Discount amount', 'model', $id_lang),
            'reduction_percent' => $this->module->l('Discount percent', 'model', $id_lang),
            'reduction_from' => $this->module->l('Discount from (yyyy-mm-dd)', 'model', $id_lang),
            'reduction_to' => $this->module->l('Discount to (yyyy-mm-dd)', 'model', $id_lang),

            'path' => $this->module->l('Path', 'model', $id_lang),
            'link' => $this->module->l('URL', 'model', $id_lang),
            'product_link' => $this->module->l('Product URL', 'model', $id_lang),
            'combination_link' => $this->module->l('Combination URL', 'model', $id_lang),
            'date_add' => $this->module->l('Creation date', 'model', $id_lang),
            'date_upd' => $this->module->l('Update date', 'model', $id_lang),

            'fix' => $this->module->l('Fixed value', 'model', $id_lang),
        );

        $features = Feature::getFeatures($id_lang);
        foreach ($features as $feature) {
            $name = $this->module->l('Feature', 'model', $id_lang).' '.$feature['name'];
            $result['feature_'.$feature['id_feature']] = $name;
        }

        $properties = array(
            'upc',
            'additional_shipping_cost',
            'condition',
            'visibility',
            'out_of_stock',
            'advanced_stock_management'
        );
        foreach ($properties as $property) {
            if (!property_exists('Product', $property)) {
                unset($result[$property]);
            }
        }

        if (version_compare(_PS_VERSION_, '1.5.3.0', '>=')) {
            $result['supplier_reference'] = $this->module->l('Default supplier reference', 'model', $id_lang);
            $result['supplier_name'] = $this->module->l('Default supplier name', 'model', $id_lang);
            $result['id_supplier'] = $this->module->l('Default supplier ID', 'model', $id_lang);
        } else {
            unset($result['supplier_reference_0']);
            unset($result['supplier_name_0']);
            unset($result['id_supplier_0']);
            unset($result['supplier_reference_1']);
            unset($result['supplier_name_1']);
            unset($result['id_supplier_1']);
            unset($result['supplier_reference_2']);
            unset($result['supplier_name_2']);
            unset($result['id_supplier_2']);
            unset($result['supplier_reference_3']);
            unset($result['supplier_name_3']);
            unset($result['id_supplier_3']);
            unset($result['supplier_reference_4']);
            unset($result['supplier_name_4']);
            unset($result['id_supplier_4']);
            unset($result['supplier_reference_5']);
            unset($result['supplier_name_5']);
            unset($result['id_supplier_5']);
            unset($result['supplier_reference_6']);
            unset($result['supplier_name_6']);
            unset($result['id_supplier_6']);
            unset($result['supplier_reference_7']);
            unset($result['supplier_name_7']);
            unset($result['id_supplier_7']);
            unset($result['supplier_reference_8']);
            unset($result['supplier_name_8']);
            unset($result['id_supplier_8']);
            unset($result['supplier_reference_9']);
            unset($result['supplier_name_9']);
            unset($result['id_supplier_9']);
            unset($result['supplier_reference_all']);
            unset($result['supplier_name_all']);
            unset($result['id_supplier_all']);
        }

        if (version_compare(_PS_VERSION_, '1.5.2.0', '>=')) {
            unset($result['tax_rate']);
        } else {
            unset($result['rate']);
            unset($result['tax_name']);
            unset($result['virtual']);
            unset($result['id_shop_default']);
        }

        if (version_compare(_PS_VERSION_, '1.4.0.0', '>=')) {
            unset($result['id_tax']);
        } else {
            unset($result['id_tax_rules_group']);
            unset($result['available_for_order']);
            unset($result['show_price']);
        }

        if (!$this->datas['attribute']) {
            unset($result['combination_name']);
            unset($result['full_name']);
            unset($result['id_product_attribute']);
            unset($result['full_product_id']);
            unset($result['attribute_0']);
            unset($result['attribute_value_0']);
            unset($result['attribute_1']);
            unset($result['attribute_value_1']);
            unset($result['attribute_2']);
            unset($result['attribute_value_2']);
            unset($result['attribute_3']);
            unset($result['attribute_value_3']);
            unset($result['attribute_4']);
            unset($result['attribute_value_4']);
            unset($result['attribute_5']);
            unset($result['attribute_value_5']);
            unset($result['attribute_6']);
            unset($result['attribute_value_6']);
            unset($result['attribute_7']);
            unset($result['attribute_value_7']);
            unset($result['attribute_8']);
            unset($result['attribute_value_8']);
            unset($result['attribute_9']);
            unset($result['attribute_value_9']);
            unset($result['attribute_all']);
            unset($result['attribute_value_all']);
            unset($result['unit_price_impact']);
            unset($result['default_on']);
            unset($result['combination_price']);
            unset($result['product_price']);
            unset($result['combination_weight']);
            unset($result['product_weight']);
            unset($result['combination_reference']);
            unset($result['product_reference']);
            unset($result['image_position_all']);
            unset($result['combination_images_count']);
            unset($result['combination_link']);
        }

        if (version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
            unset($result['attribute_all']);
            unset($result['attribute_value_all']);
            unset($result['product_link']);
            unset($result['combination_link']);
        }

        return $result;
    }

    public function __get($var)
    {
        switch ($var) {
            case 'last_run_formated':
                $date = date('Y-m-d H:i:s', $this->datas['last_run']);
                return Tools::displayDate($date, $this->module->context->language->id, true);
            default:
                return null;
        }
    }
}
