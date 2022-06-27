<?php
/**
 * 2013-2016 MADEF IT.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@madef.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    MADEF IT <contact@madef.fr>
 *  @copyright 2013-2016 MADEF IT
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

$allowed_ip = array(
    '127.0.0.1',
);

require_once dirname(__FILE__).'/../../config/config.inc.php';
require_once dirname(__FILE__).'/classes/block.php';
require_once dirname(__FILE__).'/classes/log.php';
require_once dirname(__FILE__).'/classes/cron.php';

set_error_handler('noticeHandler', E_NOTICE);
set_error_handler('warningHandler', E_WARNING);

if (!isset(Context::getContext()->cart)) {
    Context::getContext()->cart = new Cart();
}

/* Remove limit of max execution time */
/* @ini_set('max_execution_time', 0); */

/* Check we are in local */
if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ip)) {
    render(
        'Your IP ('.$_SERVER['REMOTE_ADDR'].') is not in the allowed list.'
        .' Edit this file (/modules/advancedimporter/demo.php) and add it after the line 27.'
    );
}

if (Tools::getValue('callback') == false) {
    render('Error: Attribute "callback" is missing or empty.');
}

if (!Tools::getIsset('block')) {
    render('Error: Attribute "block" is missing.');
}

$block = new Block();
$block->callback = Tools::getValue('callback');
$block->block = Tools::getValue('block');
$block->id_shop = 1;
$block->channel = 1;
$block->id_advancedimporter_flow = 0;
$created_at = new DateTime();
$block->created_at = $created_at->format('Y-m-d H:i:s');
$block->save();
try {
    $block->run('DEMO-'.rand(0, 1000));
} catch (Exception $e) {
    render('Error: '.$e->getMessage());
}

render('Process ended with success');

function warningHandler($errno, $errstr, $errfile, $errline)
{
    if (strpos($errfile, dirname(__FILE__)) === false) {
        return false;
    }

    throw new Exception("Warning: $errstr");
}
function noticeHandler($errno, $errstr, $errfile, $errline)
{
    if (strpos($errfile, dirname(__FILE__)) !== true) {
        return false;
    }

    throw new Exception("Notice: $errstr");
}

function render($message)
{
    echo $message;
    exit;
}
