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

if (Tools::getIsset('donothing')) {
    echo '// Cron launched';
    die();
}

$prefix = 'http://';
if (strpos('https://', $_SERVER['PHP_SELF']) === 0) {
    $prefix = 'https://';
}

$identifier = str_pad(rand(1, 1000), 4, '0', STR_PAD_LEFT);

set_error_handler('noticeHandler', E_NOTICE);
set_error_handler('warningHandler', E_WARNING);

if (!isset(Context::getContext()->cart)) {
    Context::getContext()->cart = new Cart();
}

$time = time();

/* Add recurent task from cron table */
$trigger = Configuration::getGlobalValue('AI_ADD_CRON_TASK')
    + Configuration::getGlobalValue('AI_ADD_CRON_TASK_EACH') * 60;
if ($time > $trigger) {
    if (class_exists('PrestaShopCollection')) {
        $cron_collection = new PrestaShopCollection('Cron');
    } else {
        $cron_collection = new Collection('Cron');
    }

    $last_check = Configuration::getGlobalValue('AI_ADD_CRON_TASK');

    if ($last_check < $time - Configuration::getGlobalValue('AI_ADD_CRON_TASK_EACH') * 60) {
        $last_check = $time - Configuration::getGlobalValue('AI_ADD_CRON_TASK_EACH') * 60;
    }

    Configuration::updateGlobalValue('AI_ADD_CRON_TASK', $time);

    $date = new DateTime();
    $date->setTimestamp($last_check);
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d H:i').':00');
    $date->add(new DateInterval('PT'.Configuration::getGlobalValue('AI_ADD_CRON_TASK_EACH').'M'));
    for ($i = 0; $i < Configuration::getGlobalValue('AI_ADD_CRON_TASK_SCALE'); ++$i) {
        $date->add(new DateInterval('PT1M'));
        $date_to_match = explode(' ', $date->format('i H d m w'));
        foreach ($cron_collection as $cron) {
            $crontime = explode(' ', $cron->crontime);
            $match = true;
            foreach ($date_to_match as $key => $value) {
                $submatch = false;
                foreach (explode(',', $crontime[$key]) as $cron_part) {
                    if ($cron_part == '*') {
                        $submatch = true;
                        break;
                    } elseif (strpos($cron_part, '*/') !== false) {
                        list($tmp, $modulo) = explode('/', $cron_part);
                        if ($value % $modulo === 0) {
                            $submatch = true;
                            break;
                        }
                    } else {
                        if ($cron_part == $value) {
                            $submatch = true;
                            break;
                        }
                    }
                }
                $match &= $submatch;
            }
            if ($match) {
                $block_collection = new Collection('Block');
                $block_collection->where('planned_at', '>', $date->format('Y-m-d H:i:0'));
                $block_collection->where('planned_at', '<', $date->format('Y-m-d H:i:59'));
                $block_collection->where('callback', '=', $cron->callback);
                $block_collection->where('block', '=', $cron->block);
                if (!count($block_collection)) {
                    $cron->plannify($date);
                }
            }
        }
    }
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
