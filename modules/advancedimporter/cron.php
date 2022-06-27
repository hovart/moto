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

require_once dirname(__FILE__).'/../../config/config.inc.php';
require_once dirname(__FILE__).'/classes/block.php';
require_once dirname(__FILE__).'/classes/log.php';
require_once dirname(__FILE__).'/classes/cron.php';
require_once dirname(__FILE__).'/classes/importercollection.php';

$cli_mode = php_sapi_name() =='cli';

if (Tools::getIsset('debug') || $cli_mode) {
    define('_AI_DEBUG_', true);
} else {
    define('_AI_DEBUG_', false);
}

if ($cli_mode) {
    define('_AI_LB_', "\n");
} else {
    define('_AI_LB_', '<br />');
}

$prefix = 'http://';
if (strpos('https://', $_SERVER['PHP_SELF']) === 0) {
    $prefix = 'https://';
}

$identifier = str_pad(rand(1, 1000), 4, '0', STR_PAD_LEFT);

set_error_handler('noticeHandler', E_NOTICE);
set_error_handler('warningHandler', E_WARNING);

$id_shop = Context::getContext()->shop->id;

if (!isset(Context::getContext()->cart)) {
    Context::getContext()->cart = new Cart();
}

/* Limit max execution time to 5 seconds + 1 operation */
$max_execution_time = (int)@ini_get('max_execution_time');
$cron_lifetime = (int) Configuration::getGlobalValue('AI_CRON_LIFETIME');
if ($max_execution_time > $cron_lifetime || !$max_execution_time) {
    $max_execution_time = $cron_lifetime;
}

if (_AI_DEBUG_) {
    echo 'Real max execution time = '.(int)@ini_get('max_execution_time')._AI_LB_;
    echo '$max_execution_time = '.var_export($max_execution_time, true)._AI_LB_;
    echo '$cron_lifetime = '.var_export($cron_lifetime, true)._AI_LB_;
}

$max_memory = (int)Tools::getMemoryLimit();
if (!$max_memory || $max_memory < 0) {
    $max_memory = 32 * 1024 * 1024;
}

if (_AI_DEBUG_) {
    echo '$max_memory = '.var_export($max_memory, true)._AI_LB_;
    echo 'memory_get_peak_usage() = '.var_export(memory_get_peak_usage(), true)._AI_LB_;
}

$start_time = microtime(true);
$channel = rand(1, Configuration::getGlobalValue('AI_NB_CHANNEL'));

if ($cli_mode) {
    if (isset($argv[1])) {
        $max_execution_time = (int)$argv[1];
    }

    if (isset($argv[2])) {
        $max_memory = (int)$argv[2];
    }

    if (isset($argv[3])) {
        $channel = (int)$argv[3];
    }
}

$lock_file = "lock/import.lock";

$time = time();

/* Auto release channel lock */
if (Configuration::getGlobalValue('AI_AUTO_RELEASE_AFTER') && file_exists($lock_file)) {
    if ($time - filemtime($lock_file) > Configuration::getGlobalValue('AI_AUTO_RELEASE_AFTER')) {
        unlink($lock_file);
    }
}

// Import files
$fp = fopen($lock_file, 'w+');
if (_AI_DEBUG_) {
    echo 'Start importing flows'._AI_LB_;
}
if (flock($fp, LOCK_EX | LOCK_NB)) {
    foreach (ImporterCollection::getInstance() as $importer) {
        new $importer();
        $time_elapsed = microtime(true) - $start_time;
        if ($max_memory != -1
            && $max_memory < memory_get_peak_usage()
            || $max_execution_time != -1
            && $time_elapsed > $max_execution_time
        ) {
            if (_AI_DEBUG_) {
                echo 'Limit reached'._AI_LB_;
            }

            fclose($fp);

            if (Tools::getIsset('reload')) {
                echo '<script>window.setTimeout(function() { location.reload(); }, 2000);</script>';
            }

            exit;
        }
    }
}
if (_AI_DEBUG_) {
    echo 'End importing flows'._AI_LB_;
}
fclose($fp);

$lock_file = "lock/$channel.lock";
$fp = fopen($lock_file, 'w+');

// Excute blocks
if (flock($fp, LOCK_EX | LOCK_NB)) {
    if (_AI_DEBUG_) {
        echo 'Lock created'._AI_LB_;
    }

    error_log("$identifier - EXEC START");

    $count = 1;
    do {
        $block = Block::getNextBlock($channel, $id_shop);

        $count++;

        if (!$block) {
            if (_AI_DEBUG_) {
                echo 'Nothing to do'._AI_LB_;
            }
            error_log("$identifier - NOTHING TO DO");
            break;
        }

        if (_AI_DEBUG_) {
            echo 'Start running block #'.$block->id._AI_LB_;
        }

        $block->run($identifier);

        if (_AI_DEBUG_) {
            echo 'End running block #'.$block->id._AI_LB_;
        }
        $time_elapsed = microtime(true) - $start_time;
        if ($count % 50 == 0) {
            Block::writeCache();
        }
    } while (($max_memory == -1|| $max_memory > memory_get_peak_usage())
        && ($max_execution_time == -1
        || $time_elapsed < $max_execution_time));

    Block::writeCache();

    fflush($fp);
    flock($fp, LOCK_UN);
} else {
    if (_AI_DEBUG_) {
        echo 'Cannot release lock'._AI_LB_;
    }
    error_log("$identifier - CHANNEL $channel LOCKED");
}
fclose($fp);

$time_elapsed = microtime(true) - $start_time;
error_log("$identifier - END - $time_elapsed");

if (Tools::getIsset('reload')) {
    echo '<script>window.setTimeout(function() { location.reload(); }, 2000);</script>';
}

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
