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

class AdminAdvancedImporterConfigurationController extends ModuleAdminController
{
    public function __construct()
    {
        $this->multishop_context = Shop::CONTEXT_ALL;
        $this->bootstrap = true;
        if (Tools::getValue('submitAddadvancedimporter_configuration')) {
            $this->processForm();
        }

        parent::__construct();
    }

    public function renderList()
    {
        $smarty = Context::getContext()->smarty;
        $smarty->assign(array(
            'AI_ADVANCED_MODE' => Configuration::getGlobalValue('AI_ADVANCED_MODE'),
            'AI_USE_API' => Configuration::getGlobalValue('AI_USE_API'),
            'AI_KEY' => Configuration::getGlobalValue('AI_KEY'),
            'AI_NB_CHANNEL' => Configuration::getGlobalValue('AI_NB_CHANNEL'),
            'AI_LOG_ENABLE' => Configuration::getGlobalValue('AI_LOG_ENABLE'),
            'AI_HISTORY_ENABLE' => Configuration::getGlobalValue('AI_HISTORY_ENABLE'),
            'AI_SYSLOG_ENABLE' => Configuration::getGlobalValue('AI_SYSLOG_ENABLE'),
            'AI_NOTICELOG_ENABLE' => Configuration::getGlobalValue('AI_NOTICELOG_ENABLE'),
            'AI_ERRORLOG_ENABLE' => Configuration::getGlobalValue('AI_ERRORLOG_ENABLE'),
            'AI_SUCCESSLOG_ENABLE' => Configuration::getGlobalValue('AI_SUCCESSLOG_ENABLE'),
            'AI_AUTO_RELEASE_AFTER' => Configuration::getGlobalValue('AI_AUTO_RELEASE_AFTER'),
            'AI_ADD_CRON_TASK_SCALE' => Configuration::getGlobalValue('AI_ADD_CRON_TASK_SCALE'),
            'AI_ADD_CRON_TASK_EACH' => Configuration::getGlobalValue('AI_ADD_CRON_TASK_EACH'),
            'AI_CRON_LIFETIME' => Configuration::getGlobalValue('AI_CRON_LIFETIME'),
            'cronUrl' => '//'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'modules/advancedimporter/cron.php',
        ));
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return $smarty->fetch(_PS_MODULE_DIR_.'advancedimporter/views/templates/admin/configuration.tpl');
        } else {
            return $smarty->fetch(_PS_MODULE_DIR_.'advancedimporter/views/templates/admin/configuration-1.5.tpl');
        }
    }

    public function processForm()
    {
        $smarty = Context::getContext()->smarty;

        $protocol = 'http';
        if (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE')) {
            $protocol = 'https';
        }

        if ((int) Tools::getValue('AI_USE_API')) {
            $smarty->assign(
                'iframe',
                'https://register.prestashopxmlimporter.madef.fr/?orderid='
                .(int) Tools::getValue('AI_KEY').'&callbackurl='
                .urlencode(
                    $protocol.'://'.preg_replace(
                        '/^(.*)\/[^\/]+\/index.php$/',
                        '$1/modules/advancedimporter',
                        $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']
                    )
                )
            );
        } else {
            $smarty->assign(
                'iframe',
                'https://unregister.prestashopxmlimporter.madef.fr/?callbackurl='
                .urlencode(
                    $protocol.'://'.preg_replace(
                        '/^(.*)\/[^\/]+\/index.php$/',
                        '$1/modules/advancedimporter',
                        $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']
                    )
                )
            );
        }

        Configuration::updateGlobalValue('AI_ADVANCED_MODE', (int) Tools::getValue('AI_ADVANCED_MODE'));
        Configuration::updateGlobalValue('AI_KEY', (int) Tools::getValue('AI_KEY'));
        if ((int) Tools::getValue('AI_NB_CHANNEL') > 0) {
            Configuration::updateGlobalValue('AI_NB_CHANNEL', (int) Tools::getValue('AI_NB_CHANNEL'));
        }

        Configuration::updateGlobalValue('AI_USE_API', (int) Tools::getValue('AI_USE_API'));
        Configuration::updateGlobalValue('AI_LOG_ENABLE', (int) Tools::getValue('AI_LOG_ENABLE'));
        Configuration::updateGlobalValue('AI_HISTORY_ENABLE', (int) Tools::getValue('AI_HISTORY_ENABLE'));
        Configuration::updateGlobalValue('AI_SYSLOG_ENABLE', (int) Tools::getValue('AI_SYSLOG_ENABLE'));
        Configuration::updateGlobalValue('AI_NOTICELOG_ENABLE', (int) Tools::getValue('AI_NOTICELOG_ENABLE'));
        Configuration::updateGlobalValue('AI_ERRORLOG_ENABLE', (int) Tools::getValue('AI_ERRORLOG_ENABLE'));
        Configuration::updateGlobalValue('AI_SUCCESSLOG_ENABLE', (int) Tools::getValue('AI_SUCCESSLOG_ENABLE'));
        Configuration::updateGlobalValue('AI_AUTO_RELEASE_AFTER', (int) Tools::getValue('AI_AUTO_RELEASE_AFTER'));
        Configuration::updateGlobalValue('AI_ADD_CRON_TASK_SCALE', (int) Tools::getValue('AI_ADD_CRON_TASK_SCALE'));
        Configuration::updateGlobalValue('AI_ADD_CRON_TASK_EACH', (int) Tools::getValue('AI_ADD_CRON_TASK_EACH'));
        Configuration::updateGlobalValue('AI_CRON_LIFETIME', (int) Tools::getValue('AI_CRON_LIFETIME'));
    }
}
