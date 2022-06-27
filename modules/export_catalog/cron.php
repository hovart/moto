<?php
/**
 * Catalog in CSV format module
 *
 * @category  Prestashop
 * @category  Module
 * @author    Samdha <contact@samdha.net>
 * @copyright Samdha
 * @license   commercial license see license.txt
 */

if (isset($argv) && !empty($argv)) {
    foreach ($argv as $k => $v) {
        if ($k != 0) {
            $it = explode('=', $v);
            if (isset($it[1])) {
                $_GET[$it[0]] = $it[1];
            } else {
                $_GET[$it[0]] = null;
            }
        }
    }
}

define('_PS_ADMIN_DIR_', getcwd());
if (!defined('STDIN')) {
    define('STDIN', 1);
}

if (file_exists(dirname(__FILE__).'/../../config/autoload.php')) {
    require_once(dirname(__FILE__).'/../../config/settings.inc.php');
    if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
        require_once(dirname(__FILE__).'/../../config/defines.inc.php');
    }

    if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
        require_once(dirname(__FILE__).'/../../config/autoload.php');
        $id_shop = Configuration::get('exp_cat_next_shop');
        if ($id_shop) {
            Configuration::set('PS_SHOP_DEFAULT', $id_shop, 0, 0);
        }
    }
    $old_error_reporting = error_reporting();
    error_reporting($old_error_reporting ^ E_NOTICE);
}

require_once(dirname(__FILE__).'/../../config/config.inc.php');
if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
    error_reporting($old_error_reporting);
    Context::getContext()->controller = new stdClass();
    Context::getContext()->controller->controller_type = 'cron'; /* avoid notice */
    if (isset($id_shop) && $id_shop) {
        Configuration::set('PS_SHOP_DEFAULT', $id_shop, 0, 0);
    }
} else {
    if (!defined('_PS_BASE_URL_') && method_exists('Tools', 'getShopDomain')) {
        define('_PS_BASE_URL_', Tools::getShopDomain(true));
    }
    if (!defined('_PS_BASE_URL_SSL_') && method_exists('Tools', 'getShopDomainSsl')) {
        define('_PS_BASE_URL_SSL_', Tools::getShopDomainSsl(true));
    }
    if (!isset($link)) {
        $link = new Link();
    }

    if (!isset($cookie)) {
        $cookie = new Cookie('ps', '');
    }

    if (!isset($cart)) {
        $cart = new Cart();
    }
}

if (($module = Module::getInstanceByName(basename(dirname(__FILE__))))
    && $module->active) {
    $module->cron(true);
}
