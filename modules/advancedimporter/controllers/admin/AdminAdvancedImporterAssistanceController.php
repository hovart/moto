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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/cron.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/csvtemplate.php';

class AdminAdvancedImporterAssistanceController extends ModuleAdminController
{
    public function __construct()
    {
        $this->multishop_context = Shop::CONTEXT_ALL;
        $this->bootstrap = true;
        parent::__construct();
    }

    public function renderList()
    {
        $smarty = Context::getContext()->smarty;

        // Php version
        $smarty->assign(array(
            'php_version' => phpversion(),
            'error_php_version' => !version_compare(phpversion(), '5.3', '>='),
        ));

        // Local?
        $smarty->assign(array(
            'error_local_host' => $_SERVER['HTTP_HOST'] == '127.0.0.1'
            || $_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '127.0.0.1',
            'host' => $_SERVER['HTTP_HOST'],
        ));

        // Smart cron enable?
        $smarty->assign(
            'error_smart_cron',
            !Configuration::getGlobalValue('AI_KEY') || !Configuration::getGlobalValue('AI_USE_API')
        );

        // Rights?
        $smarty->assign(array(
            'error_write_import_folder' => !is_writable(_PS_MODULE_DIR_.'advancedimporter/flows/import/imported/'),
            'error_write_error_folder' => !is_writable(_PS_MODULE_DIR_.'advancedimporter/flows/import/error/'),
            'error_write_queue_folder' => !is_writable(_PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'),
            'error_write_imported_folder' => !is_writable(_PS_MODULE_DIR_.'advancedimporter/flows/import/imported/'),
        ));

        // Importer?
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('Cron');
        } else {
            $collection = new Collection('Cron');
        }
        $collection->where('callback', '=', 'AIUploader::httpUploader');
        $smarty->assign(array(
            'error_importer' => false === $collection->getFirst(),
            'importer_link' => '<a href='.$this->context->link->getAdminLink('AdminAdvancedImporterCron')
                .'&addadvancedimporter_cron&uploader>'.$this->l('uploader').'</a>',
        ));

        // Csv templates?
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('CsvTemplate');
        } else {
            $collection = new Collection('CsvTemplate');
        }
        $smarty->assign(array(
            'error_csv' => 0 === count($collection),
            'csv_link' => $this->context->link->getAdminLink('AdminAdvancedImporterCsvTemplate')
                .'&addadvancedimporter_csv_template',
        ));

        /*
        // Xslt templates?
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('Xslt');
        } else {
            $collection = new Collection('Xslt');
        }
        $smarty->assign(array(
            'error_xslt' => 0 === count($collection),
            'xslt_link' => $this->context->link->getAdminLink('AdminAdvancedImporterXsltTemplate')
                .'&addadvancedimporter_csv_template',
        ));
         */

        return $smarty->fetch(_PS_MODULE_DIR_.'advancedimporter/views/templates/admin/assitance.tpl');
    }
}
