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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/xslt.php';

function upgrade_module_1_17_4($module)
{
    // Process Module upgrade to 1.17.4
    $return = Db::getInstance()->Execute(
        'ALTER TABLE '._DB_PREFIX_.'advancedimporter_xslt
        ADD COLUMN `use_tpl` TINYINT DEFAULT 0'
    );

    /*
    $xslt = new Xslt();
    $xslt->xpath_query = '/items';
    $xslt->xml = '<products>
    {foreach $root->item as $product}
        <product external-reference="{$product[\'id\']}">
            <name>{$product->name}</name>
        </product>
    {/foreach}
</products>';
    $xslt->use_tpl = 1;
    $return &= $xslt->save();
     */

    return $return;
}
