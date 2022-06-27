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

if (!defined('_PS_VERSION_')) {
    exit;
}

class AdvancedImporter extends Module
{
    public function __construct()
    {
        $this->name = 'advancedimporter';
        $this->tab = 'front_office_features';
        $this->version = '1.23.1';
        $this->author = 'MADEF IT';
        $this->need_instance = 0;
        $this->module_key = 'e991c55da91cc98de9e9e27445befc72';

        parent::__construct();

        $this->displayName = $this->l('PrestaShop XML Importer');
        $this->description = $this->l('Import every thing you need.');
    }

    public function install()
    {
        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advancedimporter_block` (
                `id_advancedimporter_block` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_shop` INT UNSIGNED NOT NULL,
                `created_at` datetime NOT NULL,
                `planned_at` datetime DEFAULT NULL,
                `treatment_start` datetime DEFAULT NULL,
                `treatment_end` datetime DEFAULT NULL,
                `channel` INT UNSIGNED NOT NULL,
                `callback` VARCHAR(255) DEFAULT NULL,
                `block` TEXT,
                `result` INT DEFAULT 1, # 1 not executed, 2 success, -1 error
                `memory` INT DEFAULT 0,
                `error` TEXT DEFAULT NULL,
                PRIMARY KEY (`id_advancedimporter_block`),
                INDEX `created_at` (`created_at`),
                INDEX `planned_at` (`treatment_start`),
                INDEX `treatment_start` (`treatment_start`),
                INDEX `treatment_end` (`treatment_start`),
                INDEX `channel` (`channel`)
            )'
        );

        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advancedimporter_log` (
                `id_advancedimporter_log` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_advancedimporter_block` INT UNSIGNED NOT NULL,
                `type` VARCHAR(20) DEFAULT NULL,
                `message` TEXT,
                `id_shop` INT UNSIGNED NOT NULL,
                `date_add` datetime DEFAULT NULL,
                PRIMARY KEY (`id_advancedimporter_log`)
            )'
        );

        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advancedimporter_cron` (
                `id_advancedimporter_cron` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `description` VARCHAR(50) NOT NULL,
                `id_shop` INT UNSIGNED NOT NULL,
                `channel` INT UNSIGNED NOT NULL,
                `callback` VARCHAR(255) NOT NULL,
                `block` TEXT,
                `crontime` VARCHAR(20) NOT NULL,
                PRIMARY KEY (`id_advancedimporter_cron`)
            )'
        );

        Db::getInstance()->execute(
            'INSERT INTO `'._DB_PREFIX_.'advancedimporter_cron`
            SET
                `description` = "'.$this->l('Import products xml flows').'",
                `id_shop` = 1,
                `channel` = 1,
                `callback` = "FlowsImporter::products",
                `block` = NULL,
                `crontime` = "* * * * *"'
        );

        Db::getInstance()->execute(
            'INSERT INTO `'._DB_PREFIX_.'advancedimporter_cron`
            SET
                `description` = "'.$this->l('Import stock xml flows').'",
                `id_shop` = 1,
                `channel` = 1,
                `callback` = "FlowsImporter::stocks",
                `block` = NULL,
                `crontime` = "* * * * *"'
        );

        Db::getInstance()->execute(
            'INSERT INTO `'._DB_PREFIX_.'advancedimporter_cron`
            SET
                `description` = "'.$this->l('Import category associations xml flows').'",
                `id_shop` = 1,
                `channel` = 1,
                `callback` = "FlowsImporter::associations",
                `block` = NULL,
                `crontime` = "* * * * *"'
        );

        Configuration::updateGlobalValue('AI_USE_API', 0);
        Configuration::updateGlobalValue('AI_NB_CHANNEL', 1);
        Configuration::updateGlobalValue('AI_LOG_ENABLE', 1);
        Configuration::updateGlobalValue('AI_SYSLOG_ENABLE', 1);
        Configuration::updateGlobalValue('AI_NOTICELOG_ENABLE', 1);
        Configuration::updateGlobalValue('AI_ERRORLOG_ENABLE', 1);
        Configuration::updateGlobalValue('AI_SUCCESSLOG_ENABLE', 1);
        Configuration::updateGlobalValue('AI_AUTO_RELEASE_AFTER', 300);
        Configuration::updateGlobalValue('AI_ADD_CRON_TASK_SCALE', 5);
        Configuration::updateGlobalValue('AI_ADD_CRON_TASK_EACH', 2);
        Configuration::updateGlobalValue('AI_CRON_LIFETIME', 30);

        $res = parent::install();

        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.10.php';
        upgrade_module_1_10($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.11.php';
        upgrade_module_1_11($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.12.php';
        upgrade_module_1_12($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.12.1.php';
        upgrade_module_1_12_1($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.14.0.php';
        upgrade_module_1_14_0($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.15.0.php';
        upgrade_module_1_15_0($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.15.2.php';
        upgrade_module_1_15_2($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.15.5.php';
        upgrade_module_1_15_5($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.16.18.php';
        upgrade_module_1_16_18($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.17.1.php';
        upgrade_module_1_17_1($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.17.3.php';
        upgrade_module_1_17_3($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.17.4.php';
        upgrade_module_1_17_4($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.18.2.php';
        upgrade_module_1_18_2($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.18.3.php';
        upgrade_module_1_18_3($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.18.4.php';
        upgrade_module_1_18_4($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.20.0.php';
        upgrade_module_1_20_0($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.20.1.php';
        upgrade_module_1_20_1($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.20.2.php';
        upgrade_module_1_20_2($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.22.0.php';
        upgrade_module_1_22_0($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.22.6.php';
        upgrade_module_1_22_6($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.22.7.php';
        upgrade_module_1_22_7($this);
        require _PS_MODULE_DIR_.'advancedimporter/upgrade/Upgrade-1.23.1.php';
        upgrade_module_1_23_1($this);

        $this->createAdminTabs();

        $res &= $this->registerHook('footer');
        $res &= $this->registerHook('displayAdminAdvancedImporterUploadView');

        return (bool) $res;
    }

    public function hookActionAdminControllerSetMedia()
    {
        $this->context->controller->addJS($this->_path.'views/js/admin.js');

        if (version_compare(_PS_VERSION_, '1.6', '>')) {
            $this->context->controller->addCSS($this->_path.'views/css/admin.css', 'all');
        }

        return '<script type="text/javascript">
                CONFIRM_DELETE = "'.htmlspecialchars($this->l('Are you sure?')).'";
            </script>';
    }

    protected function getTab($controller, $module = 'advancedimporter')
    {
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('Tab');
        } else {
            $collection = new Collection('Tab');
        }

        $collection->where('class_name', '=', $controller);
        if ($module) {
            $collection->where('module', '=', 'advancedimporter');
        }

        $tab = $collection->getFirst();
        if (!$tab) {
            $tab = new Tab();
        }
        $tab->class_name = $controller;
        $tab->module = $module;

        return $tab;
    }

    public function createAdminTabs()
    {
        $langs = Language::getLanguages();
        $id_lang = (int) Configuration::getGlobalValue('PS_LANG_DEFAULT');

        // Create tab publications
        $tab00 = $this->getTab('AdvancedImporter', null);
        $tab00->id_parent = 0;

        foreach ($langs as $l) {
            $tab00->name[$l['id_lang']] = $this->l('PrestaShop XML Importer');
        }

        $tab00->save();
        $tab_id = $tab00->id;

        // Create tab Assistance
        $tab10 = $this->getTab('AdminAdvancedImporterAssistance');
        $tab10->id_parent = $tab_id;
        $tab10->position = 10;
        foreach ($langs as $l) {
            $tab10->name[$l['id_lang']] = $this->l('Assistance');
        }

        $tab10->save();

        // Create tab Flows
        $tab20 = $this->getTab('AdminAdvancedImporterFlow');
        $tab20->id_parent = $tab_id;
        $tab20->position = 20;
        foreach ($langs as $l) {
            $tab20->name[$l['id_lang']] = $this->l('Flows');
        }

        $tab20->save();

        // Create tab Block
        $tab30 = $this->getTab('AdminAdvancedImporterBlock');
        $tab30->id_parent = $tab_id;
        $tab30->position = 30;
        foreach ($langs as $l) {
            $tab30->name[$l['id_lang']] = $this->l('Blocks');
        }

        $tab30->save();

        // Create tab Logs
        $tab35 = $this->getTab('AdminAdvancedImporterExternalReferences');
        $tab35->id_parent = $tab_id;
        $tab35->position = 35;
        foreach ($langs as $l) {
            $tab35->name[$l['id_lang']] = $this->l('External References');
        }

        $tab35->save();

        // Create tab Logs
        $tab36 = $this->getTab('AdminAdvancedImporterHistory');
        $tab36->id_parent = $tab_id;
        $tab36->position = 36;
        foreach ($langs as $l) {
            $tab36->name[$l['id_lang']] = $this->l('Object tracking');
        }

        $tab36->save();

        // Create tab Logs
        $tab40 = $this->getTab('AdminAdvancedImporterLog');
        $tab40->id_parent = $tab_id;
        $tab40->position = 40;
        foreach ($langs as $l) {
            $tab40->name[$l['id_lang']] = $this->l('Logs');
        }

        $tab40->save();

        // Create tab Cron
        $tab50 = $this->getTab('AdminAdvancedImporterCron');
        $tab50->id_parent = $tab_id;
        $tab50->position = 50;
        foreach ($langs as $l) {
            $tab50->name[$l['id_lang']] = $this->l('Recurring tasks');
        }

        $tab50->save();

        // Create tab Cron
        $tab60 = $this->getTab('AdminAdvancedImporterXslt');
        $tab60->id_parent = $tab_id;
        $tab60->position = 60;
        foreach ($langs as $l) {
            $tab60->name[$l['id_lang']] = $this->l('XML Template (XSLT)');
        }

        $tab60->save();

        // Create tab Cron
        $tab70 = $this->getTab('AdminAdvancedImporterCsvTemplate');
        $tab70->id_parent = $tab_id;
        $tab70->position = 70;
        foreach ($langs as $l) {
            $tab70->name[$l['id_lang']] = $this->l('CSV Template');
        }

        $tab70->save();

        // Create tab Configuration
        $tab80 = $this->getTab('AdminAdvancedImporterConfiguration');
        $tab80->id_parent = $tab_id;
        $tab80->position = 80;
        foreach ($langs as $l) {
            $tab80->name[$l['id_lang']] = $this->l('Configuration');
        }

        $tab80->save();

        // Create tab Upload
        $tab90 = $this->getTab('AdminAdvancedImporterUpload');
        $tab90->id_parent = $tab_id;
        $tab90->position = 90;
        foreach ($langs as $l) {
            $tab90->name[$l['id_lang']] = $this->l('Upload');
        }

        $tab90->save();

        // Create tab Template assistant
        $tab100 = $this->getTab('AdminAdvancedImporterTemplateAssistant');
        $tab100->id_parent = $tab_id;
        $tab100->active = false;
        $tab100->position = 100;
        foreach ($langs as $l) {
            $tab100->name[$l['id_lang']] = $this->l('Template assistant');
        }

        $tab100->save();

        $profiles = Profile::getProfiles($id_lang);

        if (count($profiles)) {
            foreach ($profiles as $p) {
                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab00->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab10->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab20->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab30->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab35->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab36->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab40->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab50->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab60->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                        VALUES ('.$p['id_profile'].', '.(int) $tab70->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab80->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab90->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO `'._DB_PREFIX_.'access`(`id_profile`,`id_tab`,`view`,`add`,`edit`,`delete`)
                    VALUES ('.$p['id_profile'].', '.(int) $tab100->id.',1,1,1,1)'
                );

                Db::getInstance()->Execute(
                    'INSERT IGNORE INTO '._DB_PREFIX_.'module_access(`id_profile`, `id_module`, `configure`, `view`)
                    VALUES ('.$p['id_profile'].','.(int) $this->id.',1,1)'
                );
            }
        }
    }

    public function uninstall()
    {
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'advancedimporter_block`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'advancedimporter_log`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'advancedimporter_cron`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'advancedimporter_flow`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'advancedimporter_xslt`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'advancedimporter_history`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'advancedimporter_csv_template`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'advancedimporter_externalreference`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'advancedimporter_externalreference_supplier`');

        Db::getInstance()->Execute(
            'DELETE FROM '._DB_PREFIX_.'tab
            WHERE module = "advancedimporter"
            OR class_name = "AdvancedImporter"'
        );

        Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'module_access WHERE `id_module` = '.(int) $this->id);

        return parent::uninstall();
    }

    public function hookDisplayAdminAdvancedImporterUploadView()
    {
        Context::getContext()->smarty->assign('moduleDir', _PS_MODULE_DIR_);
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return $this->display(__FILE__, 'views/templates/admin/upload.tpl');
        } else {
            return $this->display(__FILE__, 'views/templates/admin/upload-1.5.tpl');
        }
    }

    /*
     * Hack to translate class files
     */
    // $this->l('Add entry');
    // $this->l('External reference:');
    // $this->l('Attribute "%s"');
    // $this->l('Condition:');
    // $this->l('Value:');
    // $this->l('Attribute "%s":');
    // $this->l('Quantity:');
    // $this->l('Mode (set, delta):');
    // $this->l('Stock');
    // $this->l('Image url');
    // $this->l('Image url:');
    // $this->l('Features');
    // $this->l('Path of categories');
    // $this->l('Tax rule name');
    // $this->l('Tax rule name:');
    // $this->l('Price tax calculation');
    // $this->l('Prices include tax (ti ou te):');
}
