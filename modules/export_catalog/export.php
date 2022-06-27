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
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

define('_PS_ADMIN_DIR_', getcwd());

/* use $_POST because Tools::getValue() doesn't exist yet */
if (isset($_POST['id_shop']) && file_exists(dirname(__FILE__).'/../../config/autoload.php')) {
    require_once(dirname(__FILE__).'/../../config/settings.inc.php');
    if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
        require_once(dirname(__FILE__).'/../../config/defines.inc.php');
    }

    if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
        $_SERVER['SCRIPT_NAME'] = dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))).'/admin/index.php';
        $tmp_id_shop = (int)$_POST['id_shop']; /* use $_POST because Tools::getValue() doesn't exist yet */
        unset($_POST['id_shop']);
        require_once(dirname(__FILE__).'/../../config/autoload.php');
        Configuration::set('PS_SHOP_DEFAULT', $tmp_id_shop, 0, 0);

        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $tmp_http_x_forwarded_host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }
        $_SERVER['HTTP_X_FORWARDED_HOST'] = 'dummy';
    }
    $old_error_reporting = error_reporting();
    error_reporting($old_error_reporting ^ E_NOTICE);
}

require_once(dirname(__FILE__).'/../../config/config.inc.php');

if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
    if (isset($tmp_http_x_forwarded_host)) {
        $_SERVER['HTTP_X_FORWARDED_HOST'] = $tmp_http_x_forwarded_host;
    } else {
        unset($_SERVER['HTTP_X_FORWARDED_HOST']);
    }

    if (!isset($link)) {  /* Prestashop < 1.5.4 */
        $link = new Link('http://', 'http://');
        Context::getContext()->link = $link;
    }
}

if (isset($tmp_id_shop)) {
    error_reporting($old_error_reporting);
    Context::getContext()->controller = new stdClass();
    Context::getContext()->controller->controller_type = 'cron'; /* avoid notice */
    Configuration::set('PS_SHOP_DEFAULT', $tmp_id_shop, 0, 0);
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
    $module->getContent();
}
