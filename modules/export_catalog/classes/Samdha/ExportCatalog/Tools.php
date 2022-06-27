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

class Samdha_ExportCatalog_Tools
{
    public $module;

    public function __construct($module)
    {
        $this->module = $module;
    }

    /**
     * same than Category::getCategories but for all shops
     */
    public function getCategories(
        $id_lang = false,
        $active = true,
        $order = true,
        $sql_filter = '',
        $sql_sort = '',
        $sql_limit = ''
    ) {
        if (version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
            $categories = Category::getCategories($id_lang, $active, $order, $sql_filter, $sql_sort, $sql_limit);
            if ($order && version_compare(_PS_VERSION_, '1.4.0.0', '<')) {
                ksort($categories);
            }
        } else {
            if (!Validate::isBool($active)) {
                die(Tools::displayError());
            }

            $sql = '
                SELECT *
                FROM `'._DB_PREFIX_.'category` c
                INNER JOIN `'._DB_PREFIX_.'category_shop` category_shop
                    ON category_shop.id_category = c.id_category
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
                    ON c.`id_category` = cl.`id_category`
                WHERE 1 '.$sql_filter.'
                '.($id_lang ? 'AND `id_lang` = '.(int) $id_lang : '').'
                '.($active ? 'AND `active` = 1' : '').'
                '.(!$id_lang ? 'GROUP BY c.id_category' : '').'
                '.($sql_sort != '' ? $sql_sort : 'ORDER BY c.`level_depth` ASC, category_shop.`position` ASC').'
                '.$sql_limit;
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

            if (!$order) {
                return $result;
            }

            $categories = array();
            foreach ($result as $row) {
                $categories[$row['id_parent']][$row['id_category']]['infos'] = $row;
            }
        }

        return $categories;
    }

    /**
     * add categories names to products properties
     *
     * @param  integer $id_lang  language of the nmaes
     * @param  array $products products
     *         @see Samdha_ExportCatalog_Model::getProducts
     * @return array           products with categories names
     */
    public function getCategoriesNames($id_lang, $products)
    {
        static $names = array();
        $id_categories = array();

        foreach ($products as $value) {
            $key = $value['id_category_default'];
            if (!isset($names[$key])) {
                $id_categories[$key] = $key;
            }
        }

        if (!empty($id_categories)) {
            $sql = '
                SELECT cl.`id_category`, cl.`name`
                FROM `'._DB_PREFIX_.'category_lang` cl
                WHERE cl.`id_lang` = '.(int) $id_lang.'
                AND cl.`id_category` IN ('.implode(',', $id_categories).')';
            if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
                $sql .= Shop::addSqlRestrictionOnLang('cl');
            }

            $lines = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
            foreach ($lines as $line) {
                $names[$line['id_category']] = $line['name'];
            }

        }

        foreach ($products as $key => $product) {
            $products[$key]['category_name'] = $names[$product['id_category_default']];
        }

        return $products;
    }

    /**
    * Get product tags in language
    *
    * @param integer $id_product
    * @param integer $id_lang
    * @return array tags
    */
    public function getProductTags($id_product, $id_lang)
    {
        $tags = Tag::getProductTags($id_product);
        return isset($tags[$id_lang])?$tags[$id_lang]:array();
    }

    /**
    * Get product images
    *
    * @return array Product images
    */
    public function getProductImages($id_product, $id_lang)
    {
        $result = array();
        if (version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
            $sql = '
                SELECT i.`id_image`, il.`legend`
                FROM `'._DB_PREFIX_.'image` i
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il
                    ON i.`id_image` = il.`id_image`
                        AND il.`id_lang` = '.(int) $id_lang.'
                WHERE i.`id_product` = '.(int) $id_product.'
                ORDER BY `position`';
        } else {
            $sql = 'SELECT i.`id_image`, il.`legend`
                    FROM `'._DB_PREFIX_.'image` i
                    LEFT JOIN `'._DB_PREFIX_.'image_lang` il
                        ON i.`id_image` = il.`id_image`
                            AND il.`id_lang` = '.(int) $id_lang.'
                    '.Shop::addSqlAssociation('image', 'i').'
                    WHERE i.`id_product` = '.(int) $id_product.'
                    GROUP BY i.`id_image`
                    ORDER BY `position`';
        }
        $images = Db::getInstance()->ExecuteS($sql);
        foreach ($images as $image) {
            $result[] = array(
                'id_image' => $id_product.'-'.$image['id_image'],
                'legend' => $image['legend']
            );
        }

        return $result;
    }

    public function getProductCategoriesFull($id_product, $id_lang)
    {
        $result = array();
        if (version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
            $ret = array();
            $sql = '
                SELECT cp.`id_category`, cl.`name`, cl.`link_rewrite`
                FROM `'._DB_PREFIX_.'category_product` cp
                LEFT JOIN `'._DB_PREFIX_.'category` c
                    ON (c.id_category = cp.id_category)
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
                    ON (cp.`id_category` = cl.`id_category`)
                WHERE
                    cp.`id_product` = '.(int) $id_product.'
                    AND cl.`id_lang` = '.(int) $id_lang;
            $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

            foreach ($row as $val) {
                $ret[$val['id_category']] = $val;
            }
        } else {
            $ret = Product::getProductCategoriesFull($id_product, $id_lang);
        }

        $i = 0;
        foreach ($ret as $data) {
            $result['category_'.$i] = $data['link_rewrite'];
            $result['category_name_'.$i] = $data['name'];
            $result['id_category_'.$i] = $data['id_category'];
            $i++;
        }
        return $result;
    }

    /**
     * get parent categories informations:
     * - path
     * - id of parent categories
     * - friendly URLs
     * - names
     *
     * @staticvar array $datas
     * @param integer $id_lang
     * @param array $products
     * @return array
     */
    public function getProductPath($products)
    {
        static $datas = array();

        // compute missing paths
        $id_categories = array();
        foreach ($products as $product) {
            $key = $product['id_category_default'];
            if (!isset($datas[$key])) {
                $id_categories[$key] = $key;
            }
        }

        if (!empty($id_categories)) {
            $pipe = Configuration::get('PS_NAVIGATION_PIPE');
            if (!$pipe) {
                $pipe = '>';
            }
            $friendly_url = (bool) Configuration::get('PS_REWRITING_SETTINGS');
            if ($friendly_url) {
                $regexp = '/<a[^>]*\/(\d+)\-([\w\d\-]+)["|\'][^>]*>([^<]+)<\/a>/m';
            } else {
                $regexp = '/<a[^>]+id_category=(\d+)[^>]+>([^<]+)<\/a>/m';
            }

            foreach ($id_categories as $id_category) {
                if (version_compare(_PS_VERSION_, '1.4.0.0', '>=')) {
                    $path = Tools::getPath($id_category, '');
                } else {
                    $path = Tools::getFullPath($id_category, '');
                }

                $matches = array();
                preg_match_all($regexp, $path, $matches, PREG_SET_ORDER);
                $reversed_matches = array_reverse($matches);
                $depth = 0;
                foreach ($reversed_matches as $data) {
                    $datas[$id_category]['id_parent_category_'.$depth] = $data[1];
                    if ($friendly_url) {
                        $datas[$id_category]['parent_category_'.$depth] = $data[2];
                        $datas[$id_category]['parent_category_name_'.$depth] = html_entity_decode($data[3]);
                    } else {
                        $datas[$id_category]['parent_category_'.$depth] = '';
                        $datas[$id_category]['parent_category_name_'.$depth] = html_entity_decode($data[2]);
                    }
                    $depth++;
                }
                $path = trim(strip_tags($path), ' '.$pipe);
                $datas[$id_category]['path'] = html_entity_decode($path);
            }
        }

        // complete $products
        foreach ($products as $key => $product) {
            $products[$key] = array_merge($products[$key], $datas[$product['id_category_default']]);
        }
        return $products;
    }

    private $shops = array();
    /**
     * Same as Link::getImageLink with Shop management
     * @param  string $name rewrite link of the image
     * @param  string $ids id part of the image filename -
     *         can be "id_product-id_image" (legacy support, recommended)
     *         or "id_image" (new)
     * @param  string $type
     * @param  integer $id_shop
     * @return string          Image URL
     */
    private $images_links = array();
    public function getImageLink($name, $ids, $type = null, $id_shop = null)
    {
        if (!isset($this->images_links[$ids])) {
            if (version_compare(_PS_VERSION_, '1.5.0.0', '<')) {
                $link = $this->module->context->link;
                $this->images_links[$ids] = $link->getImageLink($name, $ids, $type);
            } else {
                $context = Context::getContext();
                $this->images_links[$ids] = $context->link->getImageLink($name, $ids, $type);
                if (!isset($this->shops[$id_shop])) {
                    $this->shops[$id_shop] = new Shop($id_shop);
                }
                $url = parse_url($this->images_links[$ids]);

                $shop_base_uri = $this->shops[$id_shop]->getBaseURI();
                $url['path'] = $this->strReplaceOnce(__PS_BASE_URI__, $shop_base_uri, $url['path']);
                $this->images_links[$ids] = $this->httpBuildURL($this->images_links[$ids], $url);
            }
            if (version_compare(_PS_VERSION_, '1.4.0.0', '>=')
                && Tools::substr($this->images_links[$ids], 0, 4) != 'http'
            ) {
                $this->images_links[$ids] = 'http://'.$this->images_links[$ids];
            }
            if (Tools::substr($this->images_links[$ids], 0, 8) == 'http:///') {
                // missing shop domain
                $domain = '';
                if (method_exists('Tools', 'getShopDomain')) {
                    $domain = Tools::getShopDomain(true);
                }
                if ($domain) {
                    $this->images_links[$ids] = $domain.Tools::substr($this->images_links[$ids], 7);
                }
            }
            if (Tools::substr($this->images_links[$ids], 0, 9) == 'https:///') {
                // missing shop domain
                $domain = '';
                if (method_exists('Tools', 'getShopDomainSsl')) {
                    $domain = Tools::getShopDomainSsl(true);
                }
                if (!$domain && method_exists('Tools', 'getShopDomain')) {
                    $domain = Tools::getShopDomain(true);
                }
                if ($domain) {
                    $this->images_links[$ids] = $domain.Tools::substr($this->images_links[$ids], 8);
                }
            }

        }
        return $this->images_links[$ids];
    }

    private $cache_products_suppliers = array();

    public function cacheProductsSuppliers($id_products)
    {
        foreach ($id_products as $key => $id_product) {
            if (isset($this->cache_products_suppliers[$id_product])) {
                unset($id_products[$key]);
            }
        }

        if (!empty($id_products)) {
            $sql = 'SELECT ps.*, s.name
                FROM `'._DB_PREFIX_.'product_supplier` ps
                LEFT JOIN `'._DB_PREFIX_.'supplier` s ON s.id_supplier = ps.id_supplier
                WHERE ps.id_product IN ('.implode(',', $id_products).')';

            $lines = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            foreach ($lines as $line) {
                $id_product = (int) $line['id_product'];
                $id_product_attribute = (int) $line['id_product_attribute'];
                $id_supplier = (int) $line['id_supplier'];
                if (!isset($this->cache_products_suppliers[$id_product])) {
                    $this->cache_products_suppliers[$id_product] = array();
                }
                if (!isset($this->cache_products_suppliers[$id_product][$id_product_attribute])) {
                    $this->cache_products_suppliers[$id_product][$id_product_attribute] = array();
                }
                $this->cache_products_suppliers[$id_product][$id_product_attribute][$id_supplier] = array(
                    'id_supplier' => $id_supplier,
                    'product_supplier_reference' => $line['product_supplier_reference'],
                    'product_supplier_price_te' => $line['product_supplier_price_te'],
                    'id_currency' => (int) $line['id_currency'],
                    'name' => $line['name']
                );
            }
        }
    }

    public function getProductSuppliers($id_product, $id_product_attribute, $id_supplier_default)
    {
        $result = array();
        $this->cacheProductsSuppliers(array($id_product));

        if (isset($this->cache_products_suppliers[$id_product])) {
            if (!isset($this->cache_products_suppliers[$id_product][$id_product_attribute])
                || empty($this->cache_products_suppliers[$id_product][$id_product_attribute])
            ) {
                $id_product_attribute = 0;
            }
            if (isset($this->cache_products_suppliers[$id_product][$id_product_attribute])
                && !empty($this->cache_products_suppliers[$id_product][$id_product_attribute])
            ) {
                $suppliers = $this->cache_products_suppliers[$id_product][$id_product_attribute];
                if (isset($suppliers[$id_supplier_default])) {
                    $supplier = $suppliers[$id_supplier_default];
                } else {
                    $supplier = reset($suppliers);
                }
                $result['id_supplier'] = $supplier['id_supplier'];
                $result['supplier_reference'] = $supplier['product_supplier_reference'];
                $result['supplier_name'] = $supplier['name'];
                $result['price_te'] = $supplier['product_supplier_price_te'];
                $result['id_currency'] = $supplier['id_currency'];

                $index = 0;
                foreach ($suppliers as $supplier) {
                    $result['id_supplier_'.$index] = $supplier['id_supplier'];
                    $result['supplier_reference_'.$index] = $supplier['product_supplier_reference'];
                    $result['supplier_name_'.$index] = $supplier['name'];
                    $result['price_te_'.$index] = $supplier['product_supplier_price_te'];
                    $result['id_currency_'.$index] = $supplier['id_currency'];
                    $index++;
                }
            }
        }

        return $result;
    }

    public function strReplaceOnce($search, $replace, $subject)
    {
        $result  = $subject;
        $first_char = strpos($subject, $search);
        if ($first_char !== false) {
            $before_str = Tools::substr($subject, 0, $first_char);
            $after_str = Tools::substr($subject, $first_char + Tools::strlen($search));
            $result = $before_str.$replace.$after_str;
        }
        return $result;
    }

    /**
     * HTTP Build URL
     * Combines arrays in the form of parse_url() into a new string based on specific options
     *
     * @name http_build_url
     * @param string|array $url     The existing URL as a string or result from parse_url
     * @param array $parts          Same as $url
     * @param int $flags            URLs are combined based on these
     * @param array &$new_url       If set, filled with array version of new url
     * @return string
     */
    public function httpBuildURL($url, $parts = array(), $flags = HTTP_URL_REPLACE, &$new_url = false)
    {
        // If the function doesn't already exist
        if (!function_exists('http_build_url')) {
            // If the $url is a string
            if (is_string($url)) {
                $url = parse_url($url);
            }

            // If the $parts is a string
            if (is_string($parts)) {
                $parts  = parse_url($parts);
            }

            // Scheme and Host are always replaced
            if (isset($parts['scheme'])) {
                $url['scheme'] = $parts['scheme'];
            }
            if (isset($parts['host'])) {
                $url['host'] = $parts['host'];
            }

            // (If applicable) Replace the original URL with it's new parts
            if (HTTP_URL_REPLACE & $flags) {
                // Go through each possible key
                foreach (array('user','pass','port','path','query','fragment') as $key) {
                    // If it's set in $parts, replace it in $url
                    if (isset($parts[$key])) {
                        $url[$key]  = $parts[$key];
                    }
                }
            } else {
                // Join the original URL path with the new path
                if (isset($parts['path']) && (HTTP_URL_JOIN_PATH & $flags)) {
                    if (isset($url['path']) && $url['path'] != '') {
                        // If the URL doesn't start with a slash, we need to merge
                        if ($url['path'][0] != '/') {
                            // If the path ends with a slash, store as is
                            if ('/' == $parts['path'][Tools::strlen($parts['path']) - 1]) {
                                $s_base_path = $parts['path'];
                            } else {
                                // Else trim off the file
                                $s_base_path = dirname($parts['path']); // Get just the base directory
                            }

                            // If it's empty
                            if ('' == $s_base_path) {
                                $s_base_path    = '/';
                            }

                            // Add the two together
                            $url['path'] = $s_base_path.$url['path'];

                            // Free memory
                            unset($s_base_path);
                        }

                        if (false !== strpos($url['path'], './')) {
                            // Remove any '../' and their directories
                            while (preg_match('/\w+\/\.\.\//', $url['path'])) {
                                $url['path'] = preg_replace('/\w+\/\.\.\//', '', $url['path']);
                            }

                            // Remove any './'
                            $url['path'] = str_replace('./', '', $url['path']);
                        }
                    } else {
                        $url['path'] = $parts['path'];
                    }
                }

                // Join the original query string with the new query string
                if (isset($parts['query']) && (HTTP_URL_JOIN_QUERY & $flags)) {
                    if (isset($url['query'])) {
                        $url['query'] .= '&'.$parts['query'];
                    } else {
                        $url['query'] = $parts['query'];
                    }
                }
            }

            // Strips all the applicable sections of the URL
            if (HTTP_URL_STRIP_USER & $flags) {
                unset($url['user']);
            }
            if (HTTP_URL_STRIP_PASS & $flags) {
                unset($url['pass']);
            }
            if (HTTP_URL_STRIP_PORT & $flags) {
                unset($url['port']);
            }
            if (HTTP_URL_STRIP_PATH & $flags) {
                unset($url['path']);
            }
            if (HTTP_URL_STRIP_QUERY & $flags) {
                unset($url['query']);
            }
            if (HTTP_URL_STRIP_FRAGMENT & $flags) {
                unset($url['fragment']);
            }

            // Store the new associative array in $new_url
            $new_url    = $url;

            // Combine the new elements into a string and return it
            return
                ((isset($url['scheme'])) ? $url['scheme'].'://' : '')
                .((isset($url['user'])) ? $url['user'].((isset($url['pass'])) ? ':'.$url['pass'] : '').'@' : '')
                .((isset($url['host'])) ? $url['host'] : '')
                .((isset($url['port'])) ? ':'.$url['port'] : '')
                .((isset($url['path'])) ? $url['path'] : '')
                .((isset($url['query'])) ? '?'.$url['query'] : '')
                .((isset($url['fragment'])) ? '#'.$url['fragment'] : '');
        } else {
            $function = 'http_build_url'; // for validator
            return $function($url, $parts, $flags, $new_url);
        }
    }

    private $has_products_attributes = array();
    /**
     * count and cache the number of combination products have
     *
     * @param  array $id_products products ID
     * @return void
     */
    public function hasProductsAttributes($id_products)
    {
        if (is_array($id_products) && !empty($id_products)) {
            $sql = '
                SELECT pa.`id_product`
                FROM `'._DB_PREFIX_.'product_attribute` pa ';
            if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
                $sql .= Shop::addSqlAssociation('product_attribute', 'pa');
            }
            $sql .= '
                WHERE pa.`id_product` IN ('.implode(', ', $id_products).')
                GROUP BY pa.`id_product`';
            $counts = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            foreach ($counts as $count) {
                $this->has_products_attributes[(int) $count['id_product']] = true;
            }
            foreach ($id_products as $id_product) {
                if (!isset($this->has_products_attributes[(int) $id_product])) {
                    $this->has_products_attributes[(int) $id_product] = false;
                }
            }
        }
    }

    /**
     * return if a product has conbinations
     *
     * @param  integer $id_product Product ID
     * @return boolean
     */
    public function hasProductAttributes($id_product)
    {
        $id_product = (int) $id_product;
        if (!isset($this->has_products_attributes[$id_product])) {
            $this->hasProductsAttributes(array($id_product));
        }

        if (isset($this->has_products_attributes[$id_product])) {
            $result = $this->has_products_attributes[$id_product];
        } else {
            $result = false;
        }

        return $result;
    }


    private $has_products_ean = array();
    /**
     * check and cache if products have EAN
     *
     * @param  array $id_products products ID
     * @return void
     */
    public function hasProductsEAN($id_products)
    {
        if (is_array($id_products) && !empty($id_products)) {
            $sql = '
                SELECT p.`id_product`
                FROM `'._DB_PREFIX_.'product` p ';
            if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
                $sql .= Shop::addSqlAssociation('product', 'p');
            }
            $sql .= '
                WHERE p.`id_product` IN ('.implode(', ', $id_products).')
                AND p.`ean13` NOT IN (\'0\', \'\')';
            $counts = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            foreach ($counts as $count) {
                $this->has_products_ean[(int) $count['id_product']] = true;
            }
            foreach ($id_products as $id_product) {
                if (!isset($this->has_products_ean[(int) $id_product])) {
                    $this->has_products_ean[(int) $id_product] = false;
                }
            }
        }
    }

    /**
     * return if a product has EAN
     *
     * @param  integer $id_product Product ID
     * @return boolean
     */
    public function hasProductEAN($id_product)
    {
        $id_product = (int) $id_product;
        if (!isset($this->has_products_ean[$id_product])) {
            $this->hasProductsEAN(array($id_product));
        }

        if (isset($this->has_products_ean[$id_product])) {
            $result = $this->has_products_ean[$id_product];
        } else {
            $result = false;
        }

        return $result;
    }

    private $count_products_attributes = array();
    /**
     * count and cache the number of combination products have
     *
     * @param  array $id_products products ID
     * @return void
     */
    public function countProductsAttributes($id_products/*, $no_stock = 1, $with_ean = 1*/)
    {
        if (is_array($id_products) && !empty($id_products)) {
            $use_shop = version_compare(_PS_VERSION_, '1.5.0.0', '>=');
            $sql = '
                SELECT pa.`id_product`, COUNT(pa.`id_product_attribute`) as number ';
            // if ($use_shop && $no_stock != 1)
            //  $sql .= ', SUM(sa.`quantity`) AS quantity ';
            $sql .= '
                FROM `'._DB_PREFIX_.'product_attribute` pa ';
            if ($use_shop) {
                $sql .= Shop::addSqlAssociation('product_attribute', 'pa');
            }
            // if ($no_stock != 1 && $use_shop)
            // {
            //  $sql .= ' INNER JOIN `'._DB_PREFIX_.'stock_available` sa
            //    ON (sa.id_product_attribute = pa.id_product_attribute) ';
            //  // silly isn't it
            //  $tmp_query = new DbQuery();
            //  $tmp_query->from('stock_available');
            //  StockAvailable::addSqlShopRestriction($tmp_query, null, 'sa');
            //  $tmp_sql = $tmp_query->build();
            //  $sql .= ' AND '.Tools::substr($tmp_sql, strpos($tmp_sql, 'WHERE ') + 5);
            // }
            $sql .= '
                WHERE pa.`id_product` IN ('.implode(', ', $id_products).') ';
            // if (!$use_shop)
            // {
            //  if ($no_stock == 0)
            //      $sql .= 'AND pa.`quantity` > 0 ';
            //  if ($no_stock == 2)
            //      $sql .= 'AND pa.`quantity` = 0 ';
            // }
            // if ($with_ean == 0)
            //  $sql .= 'AND pa.`ean13` = \'\' ';
            // if ($with_ean == 2)
            //  $sql .= 'AND pa.`ean13` != \'\' ';
            $sql .= '
                GROUP BY pa.`id_product`';
            // if ($use_shop)
            // {
            //  if ($no_stock == 0)
            //      $sql .= ' HAVING `quantity` > 0 ';
            //  if ($no_stock == 2)
            //      $sql .= ' HAVING `quantity` = 0 ';
            // }
            $counts = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            foreach ($counts as $count) {
                $this->count_products_attributes[(int) $count['id_product']] = (int) $count['number'];
            }
            foreach ($id_products as $id_product) {
                if (!isset($this->count_products_attributes[(int) $id_product])) {
                    $this->count_products_attributes[(int) $id_product] = 0;
                }
            }
        }
    }

    /**
     * return the number of combination a product has
     *
     * @param  integer $id_product Product ID
     * @return integer            combinations count
     */
    public function countProductAttributes($id_product/*, $no_stock = 1, $with_ean = 1*/)
    {
        $id_product = (int) $id_product;
        if (!isset($this->count_products_attributes[$id_product])) {
            $this->countProductsAttributes(array($id_product)/*, $no_stock, $with_ean*/);
        }

        if (isset($this->count_products_attributes[$id_product])) {
            $result = $this->count_products_attributes[$id_product];
        } else {
            $result = 0;
        }

        return $result;
    }

    /*
    * http://php.net/manual/fr/function.readfile.php#48683
    */
    public function readFileChunked($filename)
    {
        // how many bytes per chunk
        $chunksize = 1 * (1024 * 1024);
        $buffer = '';
        $handle = fopen($filename, 'rb');
        if ($handle === false) {
            return false;
        }

        while (!feof($handle)) {
            $buffer = fread($handle, $chunksize);
            print $buffer;
        }
        return fclose($handle);
    }

    public function getAttributeCombinations($id_lang, $products, $id_product_attributes)
    {
        $result = array();

        $version_15 = version_compare(_PS_VERSION_, '1.5.0.0', '>=');
        $tmp_product = new Product();
        $i = 1;
        foreach ($products as $product) {
            if ($this->countProductAttributes($product['id_product'])) {
                $tmp_product->id = $product['id_product'];

                // get combinations
                $tmp = $this->getProductAttributeCombinations($tmp_product, $id_lang);
                $combinations = array();
                foreach ($tmp as $combination) {
                    if (in_array($combination['id_product_attribute'], $id_product_attributes)) {
                        $combinations[] = $combination;
                    }
                }

                // replace product by combinations to $result
                if ($combinations) {
                    if (method_exists($tmp_product, 'getCombinationImages')) {
                        $images = $tmp_product->getCombinationImages($id_lang);
                    } else {
                        // PS 1.1
                        $images = array();
                    }

                    $clean_combinations = array();
                    foreach ($combinations as $combination) {
                        $id_product_attribute = $combination['id_product_attribute'];
                        if (!isset($clean_combinations[$id_product_attribute])) {
                            $combination['combination_reference'] = $combination['reference'];
                            $combination['combination_price'] = $combination['price'];
                            $combination['price'] += $product['price'];
                            $combination['combination_weight'] = $combination['weight'];
                            $combination['weight'] += $product['weight'];
                            if (isset($combination['wholesale_price']) && ($combination['wholesale_price'] == 0)) {
                                unset($combination['wholesale_price']);
                            }

                            if (isset($images[$id_product_attribute])) {
                                foreach ($images[$id_product_attribute] as $index => $image) {
                                    if ($index == 0) {
                                        $combination['id_image'] = $product['id_product'].'-'.$image['id_image'];
                                        $combination['legend'] = $image['legend'];
                                    }
                                    $combination['id_image_'.$index] = $product['id_product'].'-'.$image['id_image'];
                                    $combination['legend_'.$index] = $image['legend'];
                                    for ($index2=0; $index2 < $product['product_images_count']; $index2++) {
                                        if (isset($product['id_image_'.$index2])
                                            && ($product['id_image_'.$index2] == $combination['id_image_'.$index])
                                        ) {
                                            $combination['image_position_'.$index] = $index2 + 1;
                                            break;
                                        }
                                    }
                                }
                                $combination['images_count'] = count($images[$id_product_attribute]);
                                $combination['combination_images_count'] = $combination['images_count'];
                            } else {
                                $combination['images_count'] = 0;
                                $combination['combination_images_count'] = 0;
                            }

                            $details = array(
                                'combination_name' => $combination['group_name'].': '.$combination['attribute_name'],
                                'attribute_count' => 1,
                                'attribute_0' => $combination['group_name'],
                                'attribute_value_0' => $combination['attribute_name']
                            );
                            if ($version_15) {
                                $details['attribute_type_0'] = $this->getAttributeGroupType(
                                    $combination['id_attribute_group']
                                );
                            }
                            $clean_combinations[$id_product_attribute] = array_merge($combination, $details);
                        } else {
                            $attribute_count = $clean_combinations[$id_product_attribute]['attribute_count'];
                            $details = array(
                                'attribute_'.$attribute_count => $combination['group_name'],
                                'attribute_value_'.$attribute_count => $combination['attribute_name']
                            );
                            if ($version_15) {
                                $details['attribute_type_'.$attribute_count] = $this->getAttributeGroupType(
                                    $combination['id_attribute_group']
                                );
                            }
                            $clean_combinations[$id_product_attribute] = array_merge(
                                $clean_combinations[$id_product_attribute],
                                $details
                            );
                            $clean_combinations[$id_product_attribute]['combination_name']
                                .= ', '.$combination['group_name'].': '.$combination['attribute_name'];
                            $clean_combinations[$id_product_attribute]['attribute_count']++;
                        }
                    }
                    for ($index=0; $index < $product['product_images_count']; $index++) {
                        unset($product['id_image_'.$index]);
                        unset($product['legend_'.$index]);
                    }
                    foreach ($clean_combinations as $combination) {
                        $tmp_result = array_merge($product, $combination);
                        $tmp_result['full_name'] = $product['name'].', '.$combination['combination_name'];
                        $result[] = $tmp_result;
                    }
                }
            } else {
                $result[] = $product;
            }
            $i++;
        }
        return $result;
    }

    public function getAttributeGroupType($id_attribute_group)
    {
        static $types = null;
        if (is_null($types)) {
            $types = array();
            $sql = 'SELECT `id_attribute_group`, `group_type` FROM `'._DB_PREFIX_.'attribute_group`';
            $groups = Db::getInstance()->ExecuteS($sql);
            foreach ($groups as $group) {
                $types[$group['id_attribute_group']] = $group['group_type'];
            }
        }

        return isset($types[$id_attribute_group])?$types[$id_attribute_group]:'select';
    }

    /**
     * same than Product::getAttributeCombinations with limit
     *
     * @param  Product $product                 [description]
     * @param  int $id_lang                 [description]
     * @param  int $first_product_attribute [description]
     * @param  int $last_product_attribute  [description]
     * @return array                          the product combinations
     */
    public function getProductAttributeCombinations($product, $id_lang)
    {
        if (method_exists('Product', 'getAttributeCombinations')) {
            $combinations = $product->getAttributeCombinations($id_lang);
        } else {
            $combinations = $product->getAttributeCombinaisons($id_lang);
        }
        return $combinations;
    }

    /**
     * same as Tools::iconv
     * here for old Prestahop version
     */
    public function iconv($from, $to, $string)
    {
        // doesn't use Tools::iconv because of '&yen;', '&pound;', '&euro;'
        if (function_exists('iconv')) {
            return iconv($from, $to.'//TRANSLIT', $string);
        }

        return html_entity_decode(htmlentities($string, ENT_NOQUOTES, $from), ENT_NOQUOTES, $to);
    }

    /**
     * return running php path
     * @see http://stackoverflow.com/a/3889630
     *
     * @return string
     */
    public function getPHPExecutableFromPath()
    {
        $paths = explode(PATH_SEPARATOR, getenv('PATH'));
        foreach ($paths as $path) {
            // we need this for XAMPP (Windows)
            if (strstr($path, 'php.exe') && isset($_SERVER['WINDIR']) && @file_exists($path) && is_file($path)) {
                return $path;
            } else {
                $php_executable = $path.DIRECTORY_SEPARATOR.'php'.(isset($_SERVER['WINDIR']) ? '.exe' : '');
                if (@file_exists($php_executable) && is_file($php_executable)) {
                    return $php_executable;
                }

                $php_executable = $path.DIRECTORY_SEPARATOR.'php5'.(isset($_SERVER['WINDIR']) ? '.exe' : '');
                if (@file_exists($php_executable) && is_file($php_executable)) {
                    return $php_executable;
                }
            }
        }
        return '/usr/bin/env php'; // not found
    }


    /**
     * Build a categories tree
     *
     * @param array $indexed_categories Array with categories where product is indexed (in order to check checkbox)
     * @param array $categories Categories to list
     * @param array $current Current category
     * @param integer $id_category Current category id
     */
    public function recurseCategoryForInclude($indexed_categories, $categories, $current, $id_category = 1)
    {
        static $done, $hide_position;
        if (!isset($done)) {
            $done = array();
        }
        if (!isset($hide_position)) {
            $hide_position = !property_exists('Category', 'position');
        }

        if (!isset($done[$current['infos']['id_parent']])) {
            $done[$current['infos']['id_parent']] = 0;
        }
        $done[$current['infos']['id_parent']] += 1;

        $todo = count($categories[$current['infos']['id_parent']]);
        $done_c = $done[$current['infos']['id_parent']];

        $level = $current['infos']['level_depth'] + 1;
        $img = $level == 1 ? 'lv1' : 'lv'.$level.'_'.($todo == $done_c ? 'f' : 'b');
        if (file_exists(_PS_ROOT_DIR_.'/img/admin/'.$img.'.png')) {
            $img .= '.png';
        } elseif (file_exists(_PS_ROOT_DIR_.'/img/admin/'.$img.'.gif')) {
            $img .= '.gif';
        } else {
            $img = false;
        }

        $name = $hide_position?Category::hideCategoryPosition($current['infos']['name']):$current['infos']['name'];

        $result = '
        <tr>
            <td>
                <input
                    type="checkbox"
                    name="model[datas][categories][]"
                    class="categoryBox"
                    id="categoryBox_'.$id_category.'"
                    value="'.$id_category.'"
                    '.(in_array($id_category, $indexed_categories) ? ' checked="checked"' : '').'
                />
            </td>
            <td>
                '.$id_category.'
            </td>
            <td>';
        if ($img) {
            $result .= '<img src="../img/admin/'.$img.'" alt="" />';
        } else {
            $result .= '<img width="'.(max(1, $level-1) * 25).'" height="26" alt="" />';
        }
        $result .= ' &nbsp;<label for="categoryBox_'.$id_category.'" class="t">
                '.$this->module->samdha_tools->stripSlashes($name).'
                </label>
            </td>
        </tr>';

        if (isset($categories[$id_category])) {
            foreach ($categories[$id_category] as $key => $row) {
                if ($key != 'infos') {
                    $result .= $this->recurseCategoryForInclude($indexed_categories, $categories, $row, $key);
                }
            }
        }

        return $result;
    }

    /**
     * return feature value for a given product
     * '' if this feature doesn't exist for this product
     *
     * @param  integer $id_product
     * @param  integer $id_feature
     * @param  integer $id_lang
     * @return string             Feature value or ''
     */
    public function getProductFeatureValue($id_product, $id_feature, $id_lang)
    {
        static $values = array();
        $key = $id_product.'-'.$id_lang;
        if (!isset($values[$key])) {
            $values[$key] = array();
            $features = Product::getFrontFeaturesStatic($id_lang, $id_product);
            foreach ($features as $feature) {
                $values[$key][$feature['id_feature']] = $feature['value'];
            }
        }
        return isset($values[$key][$id_feature])?$values[$key][$id_feature]:'';
    }

    /**
     * return features for a given product
     * in Prestashop's CSV import tool
     * name:value:position
     *
     * @param  array $product
     * @return string
     */
    public function formatProductFeatures($product)
    {
        $result = '';
        if (isset($product['features']) && is_array($product['features'])) {
            $position = 1;
            $value = array();
            foreach ($product['features'] as $feature) {
                $value[] = $feature['name'].':'.$feature['value'].':'.$position;
                $position++;
            }
            $result = implode(';', $value);
        }
        return $result;
    }

    public function getFakeCustomer($id_group, $id_shop)
    {
        $customer = null;
        if ((int) $this->module->config->_id_customer) {
            $customer = new Customer((int) $this->module->config->_id_customer);
        }

        if (!Validate::isLoadedObject($customer)) {
            $customer = new Customer();
            $customer->lastname = 'Fake user';
            $customer->firstname = 'Fake user';
            $customer->email = 'fake@example.com';
            $customer->birthday = '1977-05-25';
            $customer->passwd = md5(Tools::passwdGen());
            $customer->active = false;
            $customer->deleted = true;
            $customer->id_default_group = (int) $id_group;
            if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
                $shop = new Shop((int) $id_shop);
                $customer->id_shop = $shop->id;
                $customer->id_shop_group = $shop->id_shop_group;
            }
            if (method_exists($customer, 'getFieldsRequiredDatabase')) {
                $fields = $customer->getFieldsRequiredDatabase();
                foreach ($fields as $field_detail) {
                    $field = $field_detail['field_name'];
                    if (!$customer->$field) {
                        switch (Customer::$definition['fields'][$field]['validate']) {
                            case 'isMd5':
                                $customer->$field = 'd0f67bcbce595e59799a09f95b698aeb';
                                break;
                            case 'isName':
                            case 'isPasswd':
                            case 'isGenericName':
                            case 'isCleanHtml':
                                $customer->$field = 'fakefakefakefake';
                                break;
                            case 'isEmail':
                                $customer->$field = 'fake@example.com';
                                break;
                            case 'isBirthDate':
                                $customer->$field = '2001-01-01';
                                break;
                            case 'isFloat':
                            case 'isBool':
                            case 'isUnsignedInt':
                            case 'isUnsignedId':
                                $customer->$field = 1;
                                break;
                            case 'isUrl':
                                $customer->$field = 'http://www.example.com';
                                break;
                            case 'isApe':
                                $customer->$field = '0000a';
                                break;
                            case 'isSiret':
                                $customer->$field = '12345678901234';
                                break;
                        }
                    }
                }
            }
            $customer->save();
            $this->module->config->_id_customer = $customer->id;
        } else {
            // group or shop may have been modified since last time
            $save = false;
            if ($customer->id_default_group != (int) $id_group) {
                $customer->id_default_group = (int) $id_group;
                $save = true;
            }
            if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
                $shop = new Shop((int) $id_shop);
                if (!Validate::isLoadedObject($shop)
                    && (
                        ($customer->id_shop != $shop->id)
                        || ($customer->id_shop_group != $shop->id_shop_group)
                    )
                ) {
                    $customer->id_shop = $shop->id;
                    $customer->id_shop_group = $shop->id_shop_group;
                    $save = true;
                }
            }
            if ($save) {
                $customer->save();
            }
        }

        return $customer;
    }

    /**
     * Return product shipping cost
     *
     * @param integer $id_product
     * @param integer $id_product_attribute
     * @param boolean $use_tax
     * @param integer $id_currency
     * @param integer $id_country
     * @param integer $id_group
     * @param integer $id_shop
     * @param integer $id_lang needed to create fake cart
     *
     * @return float Shipping cost
     */
    public function getProductShippingCost(
        $id_product,
        $id_product_attribute,
        $use_tax,
        $id_currency,
        $id_country,
        $id_group,
        $id_shop,
        $id_lang
    ) {
        static $cart = null, $customer = null, $address = null, $shop = null;

        Configuration::set('PS_STOCK_MANAGEMENT', false);
        if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')
            && !Validate::isLoadedObject($shop)
        ) {
            $shop = new Shop((int) $id_shop);
        }

        // create customer
        if (!Validate::isLoadedObject($customer)) {
            $customer = $this->getFakeCustomer($id_group, $id_shop);
        }

        if (class_exists('Context', false)) {
            $tmp_customer = null;
            $context = Context::getContext();
            if (isset($context->customer)) {
                $tmp_customer = $context->customer;
            }
            $context->customer = $customer;
        }

        // create address
        if (!Validate::isLoadedObject($address)) {
            if ((int) $this->module->config->_id_address) {
                $address = new Address((int) $this->module->config->_id_address);
            }

            if (!Validate::isLoadedObject($address)) {
                $address = new Address();
                $address->id_customer = $customer->id;
                $address->lastname = $customer->lastname;
                $address->firstname = $customer->firstname;
                $address->id_country = (int) $id_country;
                $address->alias = 'Fake address';
                $address->address1 = 'Fake address';
                $address->postcode = '12345';
                $address->city = 'Fake city';
                $address->deleted = true;

                $address->save();
                $this->module->config->_id_address = $address->id;
            }

            // country may have been modified since last time
            $save = false;
            if ($address->id_country != (int) $id_country) {
                $address->id_country = (int) $id_country;
                $save = true;
            }
            if ($save) {
                $address->save();
            }
        }

        // create cart
        if (!Validate::isLoadedObject($cart)) {
            if ((int) $this->module->config->_id_cart) {
                $cart = new Cart((int) $this->module->config->_id_cart);
            }

            if (!Validate::isLoadedObject($cart)) {
                $cart = new Cart();
                $cart->id_customer = $customer->id;
                $cart->id_address_delivery = $address->id;
                $cart->id_address_invoice = $address->id;
                $cart->id_lang = (int) $id_lang;
                $cart->id_currency = (int) $id_currency;
                if (!is_null($shop)) {
                    $cart->id_shop = $shop->id;
                    $cart->id_shop_group = $shop->id_shop_group;
                }
                $cart->save();
                $this->module->config->_id_cart = $cart->id;
            }

            // customer or address may have been deleted since last time
            $save = false;
            if ($cart->id_customer != $customer->id) {
                $cart->id_customer = $customer->id;
                $save = true;
            }
            if ($cart->id_address_delivery != $address->id) {
                $cart->id_address_delivery = $address->id;
                $save = true;
            }
            if ($cart->id_address_invoice != $address->id) {
                $cart->id_address_invoice = $address->id;
                $save = true;
            }
            if ($cart->id_lang != (int) $id_lang) {
                $cart->id_lang = (int) $id_lang;
                $save = true;
            }
            if ($cart->id_currency != (int) $id_currency) {
                $cart->id_currency = (int) $id_currency;
                $save = true;
            }
            if (!is_null($shop)
                && (
                    ($cart->id_shop != $shop->id)
                    || ($cart->id_shop_group != $shop->id_shop_group)
                )
            ) {
                $cart->id_shop = $shop->id;
                $cart->id_shop_group = $shop->id_shop_group;
                $save = true;
            }
            if ($save) {
                $cart->save();
            }
        }

        // empty cart
        $products = $cart->getProducts();
        foreach ($products as $product) {
            $cart->deleteProduct(
                $product['id_product'],
                $product['id_product_attribute'],
                isset($product['id_customization'])?$product['id_customization']:null
            );
        }

        // add product
        $minimal_quantity = 1;
        if (!empty($id_product_attribute)
            && method_exists('Attribute', 'getAttributeMinimalQty')
        ) {
            $minimal_quantity = (int) Attribute::getAttributeMinimalQty($id_product_attribute);
        } elseif (property_exists('Product', 'minimal_quantity')) {
            $product = new Product($id_product, false, (int) $id_lang, $id_shop);
            $minimal_quantity = (int) $product->minimal_quantity;
        }

        $cart->updateQty(max($minimal_quantity, 1), $id_product, $id_product_attribute);

        // bug in Cart::getPackageShippingCost if default carrier is not active
        $id_carrier = null;
        $carrier = $this->getCarrier(Configuration::get('PS_CARRIER_DEFAULT'));
        if (!$carrier->active) {
            $country = new Country($id_country);
            $id_zone = (int) $country->id_zone;
            $carrier = $this->getBestCarrier(
                $cart->getOrderTotal($use_tax, 7),
                $cart->getTotalWeight(),
                $id_zone,
                $id_currency,
                $id_group
            );
            $id_carrier = $carrier->id;
        }

        // at last get shipping cost
        if (method_exists($cart, 'getPackageShippingCost')) {
            $result = $cart->getPackageShippingCost($id_carrier, $use_tax);
        } else {
            $result = $cart->getOrderShippingCost($id_carrier, $use_tax);
        }

        if (isset($context)) {
            $context->customer = $tmp_customer;
        }

        return $result;
    }

    public function getCarrier($id_carrier)
    {
        static $carriers = array();
        if (!isset($carriers[$id_carrier])) {
            $carriers[$id_carrier] = new Carrier($id_carrier);
        }
        return $carriers[$id_carrier];
    }

    public function getBestCarrier($price, $weight, $id_zone, $id_currency, $id_group)
    {
        static $carriers;
        static $id_default_carrier;
        $id_carrier = null;

        if (!isset($id_default_carrier)) {
            $id_default_carrier = (int) Configuration::get('PS_CARRIER_DEFAULT');
        }

        if ($id_default_carrier > 0) {
            $carrier = $this->getCarrier($id_default_carrier);
            if ($carrier->active
                && (
                    (
                        Configuration::get('PS_SHIPPING_METHOD')
                        && Carrier::checkDeliveryPriceByWeight($id_default_carrier, $weight, $id_zone)
                    )
                    || (
                        !Configuration::get('PS_SHIPPING_METHOD')
                        && Carrier::checkDeliveryPriceByPrice($id_default_carrier, $price, $id_zone, $id_currency)
                    )
                )
            ) {
                $id_carrier = $id_default_carrier;
            }
        }

        if (empty($id_carrier)) {
            if (!isset($carriers)) {
                $carriers = Carrier::getCarriers(
                    (int) Configuration::get('PS_LANG_DEFAULT'),
                    true,
                    false,
                    (int) $id_zone,
                    array($id_group)
                );
            }
            $best_shipping = PHP_INT_MAX; // take the cheapest carrier
            foreach ($carriers as $row) {
                if ($row['id_carrier'] == $id_default_carrier) {
                    continue;
                }

                $carrier = $this->getCarrier($row['id_carrier']);
                // Get only carriers that are compliant with shipping method
                if ((
                        Configuration::get('PS_SHIPPING_METHOD')
                        && $carrier->getMaxDeliveryPriceByWeight($id_zone) === false
                    ) || (
                        !Configuration::get('PS_SHIPPING_METHOD')
                        && $carrier->getMaxDeliveryPriceByPrice($id_zone) === false
                    )
                ) {
                    continue;
                }

                // If out-of-range behavior carrier is set on "Desactivate carrier"
                if ($row['range_behavior']) {
                    // Get only carriers that have a range compatible with cart
                    if ((
                            Configuration::get('PS_SHIPPING_METHOD')
                            && !Carrier::checkDeliveryPriceByWeight($carrier->id, $weight, $id_zone)
                        ) || (
                            !Configuration::get('PS_SHIPPING_METHOD')
                            && !Carrier::checkDeliveryPriceByPrice($carrier->id, $price, $id_zone, $id_currency)
                        )
                    ) {
                        continue;
                    }
                }
                if ((int) Configuration::get('PS_SHIPPING_METHOD')) {
                    $shipping = $carrier->getDeliveryPriceByWeight($weight, $id_zone);
                    if ($shipping <= $best_shipping) {
                        $id_carrier = $carrier->id;
                        $best_shipping = $shipping;
                    }
                } else {
                    $shipping = $carrier->getDeliveryPriceByPrice($price, $id_zone, $id_currency);
                    if ($shipping <= $best_shipping) {
                        $id_carrier = $carrier->id;
                        $best_shipping = $shipping;
                    }
                }
            }
        }
        if (empty($id_carrier)) {
            $id_carrier = $id_default_carrier;
        }

        return $this->getCarrier($id_carrier);
    }

    /**
     * Return product shipping cost (simple way)
     * may be wrong
     *
     * @param float $price
     * @param float $weight
     * @param boolean $use_tax
     * @param integer $id_currency
     * @param integer $id_country
     * @param integer $id_group
     * @param integer $id_shop
     * @param integer $additional_shipping_cost
     *
     * @return float Shipping cost
     */
    public function getProductShippingCostSimple(
        $price,
        $weight,
        $use_tax,
        $id_currency,
        $id_country,
        $id_group,
        /*$id_shop,*/
        $additional_shipping_cost
    ) {
        static $id_zone = null, $taxes = array();

        $shipping_cost = $additional_shipping_cost;
        if (is_null($id_zone)) {
            $country = new Country($id_country);
            $id_zone = (int) $country->id_zone;
        }

        $carrier = $this->getBestCarrier($price, $weight, $id_zone, $id_currency, $id_group);

        if (!Validate::isLoadedObject($carrier)) {
            die(Tools::displayError('Fatal error: "no default carrier"'));
        }

        if (!$carrier->active) {
            return $shipping_cost;
        }

        // Select carrier tax
        if (version_compare(_PS_VERSION_, '1.4.0.0', '>=')) {
            if (!defined('_PS_TAX_')) {
                // Prestashop 1.5
                define('_PS_TAX_', Configuration::get('PS_TAX'));
            }

            // Free fees if free carrier
            if ($carrier->is_free == 1) {
                return 0;
            }

            // Select carrier tax
            if ($use_tax && _PS_TAX_) {
                $carrier_tax = Tax::getCarrierTaxRate((int) $carrier->id);
            }
        } else {
            if ($use_tax && $carrier->id_tax) {
                if (!isset($taxes[$carrier->id_tax])) {
                    $taxes[$carrier->id_tax] = new Tax((int) $carrier->id_tax);
                }

                $tax = $taxes[$carrier->id_tax];
                if (Validate::isLoadedObject($tax)
                    && Tax::zoneHasTax((int) $tax->id, (int) $id_zone)
                    && !Tax::excludeTaxeOption()
                ) {
                    $carrier_tax = $tax->rate;
                }
            }
        }
        $configuration = Configuration::getMultiple(array(
            'PS_SHIPPING_FREE_PRICE',
            'PS_SHIPPING_HANDLING',
            'PS_SHIPPING_METHOD',
            'PS_SHIPPING_FREE_WEIGHT'
        ));
        // Free fees
        $free_fees_price = 0;
        if (isset($configuration['PS_SHIPPING_FREE_PRICE'])) {
            $free_fees_price = (float) Tools::convertPrice(
                (float) $configuration['PS_SHIPPING_FREE_PRICE'],
                $id_currency
            );
        }

        if ($price >= $free_fees_price && $free_fees_price > 0) {
            return $shipping_cost;
        }

        if (isset($configuration['PS_SHIPPING_FREE_WEIGHT'])
            && $weight >= (float) $configuration['PS_SHIPPING_FREE_WEIGHT']
            && (float) $configuration['PS_SHIPPING_FREE_WEIGHT'] > 0
        ) {
            return $shipping_cost;
        }

        // Get shipping cost using correct method
        if ($carrier->range_behavior) {
            if ((
                    Configuration::get('PS_SHIPPING_METHOD')
                    && !Carrier::checkDeliveryPriceByWeight($carrier->id, $weight, $id_zone)
                ) || (
                    !Configuration::get('PS_SHIPPING_METHOD')
                    && !Carrier::checkDeliveryPriceByPrice($carrier->id, $price, $id_zone, $id_currency)
                )
            ) {
                $shipping_cost += 0;
            } else {
                if ((int) $configuration['PS_SHIPPING_METHOD']) {
                    $shipping_cost += $carrier->getDeliveryPriceByWeight($weight, $id_zone);
                } else {
                    $shipping_cost += $carrier->getDeliveryPriceByPrice($price, $id_zone, $id_currency);
                }
            }
        } else {
            if ((int) $configuration['PS_SHIPPING_METHOD']) {
                $shipping_cost += $carrier->getDeliveryPriceByWeight($weight, $id_zone);
            } else {
                $shipping_cost += $carrier->getDeliveryPriceByPrice($price, $id_zone, $id_currency);
            }
        }

        // Adding handling charges
        if (isset($configuration['PS_SHIPPING_HANDLING'])
            && $carrier->shipping_handling
        ) {
            $shipping_cost += (float) $configuration['PS_SHIPPING_HANDLING'];
        }

        $shipping_cost_final = Tools::convertPrice($shipping_cost, $id_currency);

        // Apply tax
        if (isset($carrier_tax)) {
            $shipping_cost_final *= 1 + ($carrier_tax / 100);
        }

        return (float) Tools::ps_round((float) $shipping_cost_final, 2);
    }

    private function importOldConfig()
    {
        $model = new Samdha_ExportCatalog_Model(_PS_ROOT_DIR_.$this->module->config->_directory, $this->module);

        $model->name = $this->module->l('New model', 'tools');
        if (Configuration::get($this->module->short_name.'_id_lang') !== false) {
            $model->datas['id_lang'] = Configuration::get($this->module->short_name.'_id_lang');
            Configuration::deleteByName($this->module->short_name.'_id_lang');
        }
        if (Configuration::get($this->module->short_name.'_attribute') !== false) {
            $model->datas['attribute'] = Configuration::get($this->module->short_name.'_attribute');
            Configuration::deleteByName($this->module->short_name.'_attribute');
        }
        if (Configuration::get($this->module->short_name.'_filename') !== false) {
            $model->datas['filename'] = Configuration::get($this->module->short_name.'_filename');
            Configuration::deleteByName($this->module->short_name.'_filename');
        }
        if (Configuration::get($this->module->short_name.'_separator') !== false) {
            $model->datas['separator'] = Configuration::get($this->module->short_name.'_separator');
            Configuration::deleteByName($this->module->short_name.'_separator');
        }
        if (Configuration::get($this->module->short_name.'_header') !== false) {
            $model->datas['header'] = Configuration::get($this->module->short_name.'_header');
            Configuration::deleteByName($this->module->short_name.'_header');
        }
        if (Configuration::get($this->module->short_name.'_charset') !== false) {
            $model->datas['charset'] = Configuration::get($this->module->short_name.'_charset');
            Configuration::deleteByName($this->module->short_name.'_charset');
        }
        if (Configuration::get($this->module->short_name.'_fields') !== false) {
            $possible_fields = $model->getPossibleFields();
            $fields = explode(',', Configuration::get($this->module->short_name.'_fields'));
            $model->datas['fields'] = array();
            foreach ($fields as $field) {
                $model->datas['fields'][] = array(
                    'id' => $field,
                    'value' => '',
                    'title' => $possible_fields[$field]
                );
            }
            Configuration::deleteByName($this->module->short_name.'_fields');
        }
        if (Configuration::get($this->module->short_name.'_inactive') !== false) {
            $model->datas['inactive'] = Configuration::get($this->module->short_name.'_inactive');
            Configuration::deleteByName($this->module->short_name.'_inactive');
        }
        if (Configuration::get($this->module->short_name.'_no_stock') !== false) {
            $model->datas['no_stock'] = Configuration::get($this->module->short_name.'_no_stock');
            Configuration::deleteByName($this->module->short_name.'_no_stock');
        }
        if (Configuration::get($this->module->short_name.'_size') !== false) {
            $model->datas['size'] = Configuration::get($this->module->short_name.'_size');
            Configuration::deleteByName($this->module->short_name.'_size');
        }
        if (Configuration::get($this->module->short_name.'_categories') !== false) {
            $model->datas['categories'] = explode(',', Configuration::get($this->module->short_name.'_categories'));
            Configuration::deleteByName($this->module->short_name.'_categories');
        }
        if (Configuration::get($this->module->short_name.'_decimal') !== false) {
            $model->datas['decimal'] = Configuration::get($this->module->short_name.'_decimal');
            Configuration::deleteByName($this->module->short_name.'_decimal');
        }
        if (Configuration::get($this->module->short_name.'_precision') !== false) {
            $model->datas['precision'] = Configuration::get($this->module->short_name.'_precision');
            Configuration::deleteByName($this->module->short_name.'_precision');
        }
        if (Configuration::get($this->module->short_name.'_id_currency') !== false) {
            $model->datas['id_currency'] = Configuration::get($this->module->short_name.'_id_currency');
            Configuration::deleteByName($this->module->short_name.'_id_currency');
        }
        if (Configuration::get($this->module->short_name.'_id_shop') !== false) {
            $model->datas['id_shop'] = Configuration::get($this->module->short_name.'_id_shop');
            Configuration::deleteByName($this->module->short_name.'_id_shop');
        }
        if (Configuration::get($this->module->short_name.'_id_group') !== false) {
            $model->datas['id_group'] = Configuration::get($this->module->short_name.'_id_group');
            Configuration::deleteByName($this->module->short_name.'_id_group');
        }
        if (Configuration::get($this->module->short_name.'_id_country') !== false) {
            $model->datas['id_country'] = Configuration::get($this->module->short_name.'_id_country');
            Configuration::deleteByName($this->module->short_name.'_id_country');
        }
        $model->save();

        if (Configuration::get($this->module->short_name.'_schedule')) {
            $export = new Samdha_ExportCatalog_Export(_PS_ROOT_DIR_.$this->module->config->_directory, $this->module);

            $export->name = $this->module->l('New export', 'tools');
            $export->datas['model'] = $model->filename;
            if (Configuration::get($this->module->short_name.'_id_employee') !== false) {
                $export->datas['id_employee'] = Configuration::get($this->module->short_name.'_id_employee');
                Configuration::deleteByName($this->module->short_name.'_id_employee');
            }
            if (Configuration::get($this->module->short_name.'_minute') !== false) {
                $export->datas['minute'] = Configuration::get($this->module->short_name.'_minute');
                Configuration::deleteByName($this->module->short_name.'_minute');
            }
            if (Configuration::get($this->module->short_name.'_hour') !== false) {
                $export->datas['hour'] = Configuration::get($this->module->short_name.'_hour');
                Configuration::deleteByName($this->module->short_name.'_hour');
            }
            if (Configuration::get($this->module->short_name.'_days') !== false) {
                $export->datas['days'] = explode(',', Configuration::get($this->module->short_name.'_days'));
                Configuration::deleteByName($this->module->short_name.'_days');
            }
            if (Configuration::get($this->module->short_name.'_folder') !== false) {
                $export->datas['folder'] = Configuration::get($this->module->short_name.'_folder');
                Configuration::deleteByName($this->module->short_name.'_folder');
            }
            $export->save();
        }
    }

    /**
     * remove error message added by displayFatalError()
     * in Prestashop 1.5.3.x
     *
     * @since 1.2.4.0
     */
    public function fixDisplayFatalError()
    {
        $buffer = ob_get_contents();
        $position = strpos($buffer, '}[PrestaShop] Fatal error in module ');
        if ($position !== false) {
            ob_clean();
            $buffer = Tools::substr($buffer, 0, $position + 1);
            echo $buffer;
        }
    }

    /**
     * Compatibility PHP 5.0
     * http://www.php.net/manual/fr/function.fputcsv.php#84783
     */
    public function fPutCSV(&$handle, $fields = array(), $delimiter = ',', $enclosure = '"', $force_enclosure = false)
    {
        if (!$enclosure) {
            return fwrite($handle, implode($delimiter, $fields)."\n");
        }
        if (function_exists('fputcsv') && !$force_enclosure) {
            return fputcsv($handle, $fields, $delimiter, $enclosure);
        }

        // Sanity Check
        if (!is_resource($handle)) {
            trigger_error('fputcsv() expects parameter 1 to be resource, '.gettype($handle).' given', E_USER_WARNING);
            return false;
        }

        if ($delimiter != null) {
            if (Tools::strlen($delimiter) < 1) {
                trigger_error('delimiter must be a character', E_USER_WARNING);
                return false;
            } elseif (Tools::strlen($delimiter) > 1) {
                trigger_error('delimiter must be a single character', E_USER_NOTICE);
            }

            /* use first character from string */
            $delimiter = $delimiter[0];
        }

        if ($enclosure === '') {
            $enclosure = null;
        }

        if ($enclosure != null) {
            if (Tools::strlen($enclosure) < 1) {
                trigger_error('enclosure must be a character', E_USER_WARNING);
                return false;
            } elseif (Tools::strlen($enclosure) > 1) {
                trigger_error('enclosure must be a single character', E_USER_NOTICE);
            }

            /* use first character from string */
            $enclosure = $enclosure[0];
        }

        $i = 0;
        $csvline = '';
        $escape_char = '\\';
        $field_cnt = count($fields);
        $enc_is_quote = in_array($enclosure, array('"',"'"));
        reset($fields);

        foreach ($fields as $field) {
            /* enclose a field that contains a delimiter, an enclosure character, or a newline */
            if ($force_enclosure
                || (is_string($field)
                    && (
                        strpos($field, $delimiter) !== false
                        || strpos($field, $enclosure) !== false
                        || strpos($field, $escape_char) !== false
                        || strpos($field, "\n") !== false
                        || strpos($field, "\r") !== false
                        || strpos($field, "\t") !== false
                        || strpos($field, ' ') !== false
                    )
                )
            ) {
                $field_len = Tools::strlen($field);
                $escaped = 0;

                $csvline .= $enclosure;
                for ($ch = 0; $ch < $field_len; $ch++) {
                    if (Tools::substr($field, $ch, 1) == $escape_char
                        && Tools::substr($field, $ch + 1, 1) == $enclosure && $enc_is_quote
                    ) {
                        continue;
                    } elseif (Tools::substr($field, $ch, 1) == $escape_char) {
                        $escaped = 1;
                    } elseif (!$escaped && Tools::substr($field, $ch, 1) == $enclosure) {
                        $csvline .= $enclosure;
                    } else {
                        $escaped = 0;
                    }
                    $csvline .= Tools::substr($field, $ch, 1);
                }
                $csvline .= $enclosure;
            } else {
                $csvline .= $field;
            }

            if ($i++ != $field_cnt) {
                $csvline .= $delimiter;
            }
        }

        $csvline .= "\n";

        return fwrite($handle, $csvline);
    }

    public function encode($to_encode)
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
        $encoded = '';
        $padding = '';
        $count = Tools::strlen($to_encode) % 3;

        if ($count > 0) {
            for (; $count < 3; $count++) {
                $padding .= '=';
                $to_encode .= "\0";
            }
        }

        for ($position = 0; $position < Tools::strlen($to_encode); $position += 3) {
            $n = (ord($to_encode[$position]) << 16)
                + (ord($to_encode[$position + 1]) << 8)
                + ord($to_encode[$position + 2]);
            $n1 = ($n >> 18) & 63;
            $n2 = ($n >> 12) & 63;
            $n3 = ($n >> 6) & 63;
            $n4 = $n & 63;

            $encoded .= $chars[$n1].$chars[$n2].$chars[$n3].$chars[$n4];
        }

        return Tools::substr($encoded, 0, Tools::strlen($encoded) - Tools::strlen($padding)).$padding;
    }

    public function decode($to_decode)
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
        $to_decode = preg_replace('[^'.$chars.'=]', '', $to_decode);

        $padding = (Tools::substr($to_decode, -1) == '=')?((Tools::substr($to_decode, -2, 2) == '==')?'AA':'A'):'';
        $decoded = '';
        $to_decode = Tools::substr($to_decode, 0, Tools::strlen($to_decode) - Tools::strlen($padding)).$padding;

        $positions = array_flip(str_split($chars));
        for ($position = 0; $position < Tools::strlen($to_decode); $position += 4) {
            $n = ($positions[$to_decode[$position]] << 18)
                + ($positions[$to_decode[$position + 1]] << 12)
                + ($positions[$to_decode[$position + 2]] << 6)
                + $positions[$to_decode[$position + 3]];
            $decoded .= chr(($n >> 16) & 0xFF).chr(($n >> 8) & 0xFF).chr($n & 0xFF);
        }

        return Tools::substr($decoded, 0, Tools::strlen($decoded) - Tools::strlen($padding));
    }

    public function copyToFTP($local_filename, $remote_filename)
    {
        if ($this->module->samdha_tools->canUseCurl()) {
            $ch = curl_init();
            $fp = fopen($local_filename, 'r');
            curl_setopt($ch, CURLOPT_URL, $remote_filename);
            curl_setopt($ch, CURLOPT_UPLOAD, 1);
            curl_setopt($ch, CURLOPT_INFILE, $fp);
            curl_setopt($ch, CURLOPT_INFILESIZE, filesize($local_filename));
            curl_exec($ch);
            curl_close($ch);
        } else {
            $this->module->samdha_tools->copy($local_filename, $remote_filename);
        }
    }
}

/* If the function doesn't already exist */
if (!function_exists('http_build_url')) {
    /* Define constants */
    define('HTTP_URL_REPLACE', 0x0001); /* Replace every part of the first URL when there's one of the second URL */
    define('HTTP_URL_JOIN_PATH', 0x0002); /* Join relative paths */
    define('HTTP_URL_JOIN_QUERY', 0x0004); /* Join query strings */
    define('HTTP_URL_STRIP_USER', 0x0008); /* Strip any user authentication information */
    define('HTTP_URL_STRIP_PASS', 0x0010); /* Strip any password authentication information */
    define('HTTP_URL_STRIP_PORT', 0x0020); /* Strip explicit port numbers */
    define('HTTP_URL_STRIP_PATH', 0x0040); /* Strip complete path */
    define('HTTP_URL_STRIP_QUERY', 0x0080); /* Strip query string */
    define('HTTP_URL_STRIP_FRAGMENT', 0x0100); /* Strip any fragments (#identifier) */

    /* Combination constants */
    define('HTTP_URL_STRIP_AUTH', HTTP_URL_STRIP_USER | HTTP_URL_STRIP_PASS);
    define(
        'HTTP_URL_STRIP_ALL',
        HTTP_URL_STRIP_AUTH | HTTP_URL_STRIP_PORT | HTTP_URL_STRIP_QUERY | HTTP_URL_STRIP_FRAGMENT
    );
}
