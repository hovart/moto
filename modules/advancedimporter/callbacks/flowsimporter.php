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

class FlowsImporter
{
    public static $id_advancedimporter_block;
    public static $id_advancedimporter_flow;
    public static $id_shop;

    public static function products()
    {
        require_once _PS_MODULE_DIR_.'advancedimporter/importer/productflowsimport.php';
        new ProductFlowsImport();
    }

    public static function associations()
    {
        require_once _PS_MODULE_DIR_.'advancedimporter/importer/associationflowsimport.php';
        new AssociationFlowsImport();
    }

    public static function stocks()
    {
        require_once _PS_MODULE_DIR_.'advancedimporter/importer/stockflowsimport.php';
        new StockFlowsImport();
    }

    public static function objects()
    {
        require_once _PS_MODULE_DIR_.'advancedimporter/importer/objectflowsimport.php';
        new ObjectFlowsImport();
    }

    public static function deletes()
    {
        require_once _PS_MODULE_DIR_.'advancedimporter/importer/deleteflowsimport.php';
        new DeleteFlowsImport();
    }

    public static function attachments()
    {
        require_once _PS_MODULE_DIR_.'advancedimporter/importer/attachmentflowsimport.php';
        new AttachmentFlowsImport();
    }
}
