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

class AdminAdvancedImporterCronController extends ModuleAdminController
{
    protected $colorOnBackground = true;
    protected $color_on_background = true; /* Ne sert que si un jour PS réutilise la norme */

    public function __construct()
    {
        $this->table = 'advancedimporter_cron';
        $this->className = 'Cron';

        $this->module = 'advancedimporter';
        $this->multishop_context = Shop::CONTEXT_ALL;

        $this->_orderBy = 'id_advancedimporter_cron';
        $this->_orderWay = 'DESC';

        $this->bootstrap = true;

        $this->fields_list = array(
            'id_advancedimporter_cron' => array('title' => $this->l('ID'), 'align' => 'center', 'width' => 30),
            'description' => array('title' => $this->l('Description'), 'align' => 'left'),
            'callback' => array('title' => $this->l('Callback'), 'align' => 'center', 'width' => 120),
            'crontime' => array('title' => $this->l('Crontime'), 'width' => 120),
        );

        $this->addRowAction('add');
        $this->addRowAction('edit');
        $this->addRowAction('run');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete'),
                'confirm' => $this->l('Are you sure?'),
            ),
        );

        parent::__construct();

        if (Tools::getIsset('run')) {
            $this->runAction();
        }
    }

    public function runAction()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        require_once _PS_MODULE_DIR_.'advancedimporter/classes/log.php';
        require_once _PS_MODULE_DIR_.'advancedimporter/classes/block.php';
        $obj->plannify()->run();
        Block::writeCache();

        return Tools::redirectAdmin('?controller=AdminAdvancedImporterCron&token='.$this->token);
    }

    /**
     * Custom action icon "run".
     */
    public function displayRunLink($token = null, $id = null)
    {
        if (!array_key_exists('plannify', self::$cache_lang)) {
            self::$cache_lang['plannify'] = $this->l('Execute');
        }

        $this->context->smarty->assign(array(
            'module_dir' => __PS_BASE_URI__.'modules/advancedimporter/',
            'href' => self::$currentIndex.
                '&'.$this->identifier.'='.$id.
                '&run=1&token='.($token != null ? $token : $this->token),
            'action' => self::$cache_lang['plannify'],
        ));

        if (version_compare(_PS_VERSION_, '1.6', '>')) {
            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_.'advancedimporter/views/templates/admin/list_action/run.tpl'
            );
        } else {
            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_.'advancedimporter/views/templates/admin/list_action/run-1.5.tpl'
            );
        }
    }

    public function renderFtpUploadForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $obj->callback = 'AIUploader::ftpUploader';

        if (empty($obj->block)) {
            $obj->block = '{}';
        }

        $block = Tools::jsonDecode($obj->block);

        foreach (array('sourcedir', 'user', 'password', 'port', 'host') as $key) {
            if (isset($obj->$key)) {
                $block->$key = $obj->$key;
            } elseif (isset($block->$key)) {
                $obj->$key = $block->$key;
            }
        }

        $obj->block = Tools::jsonEncode($block);

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Description :'),
            'size' => 92,
            'id' => 'description',
            'name' => 'description',
        );

        $this->fields_form['input'][] = array(
            'type' => 'hidden',
            'id' => 'callback',
            'name' => 'callback',
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Cron Time :'),
            'size' => 92,
            'id' => 'crontime',
            'name' => 'crontime',
            'desc' => $this->l('From example: 00 1 * * * for each day at 1h am, or */15 * * * 1 for each quarter of the monday')
            .'<br />'.$this->l('Format: <Minute> <hour> <number of the day> <number of the month> <number of the day of week>'),
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('User:'),
            'size' => 92,
            'id' => 'user',
            'name' => 'user',
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Password:'),
            'size' => 92,
            'id' => 'password',
            'name' => 'password',
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Host:'),
            'size' => 92,
            'id' => 'host',
            'name' => 'host',
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Port:'),
            'size' => 2,
            'id' => 'port',
            'name' => 'port',
            'desc' => $this->l('Default: 21'),
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Source directory:'),
            'size' => 92,
            'id' => 'sourcedir',
            'name' => 'sourcedir',
            'desc' => $this->l('Default: .'),
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Channel :'),
            'size' => 92,
            'id' => 'channel',
            'name' => 'channel',
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Id Shop :'),
            'size' => 92,
            'id' => 'id_shop',
            'name' => 'id_shop',
        );

        $this->fields_form['submit'] = array(
                'title' => $this->l('Save'),
                'class' => 'button btn btn-default',
        );

        return parent::renderForm();
    }

    public function renderHttpUploadForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $obj->callback = 'AIUploader::httpUploader';

        if (empty($obj->block)) {
            $obj->block = '{}';
        }

        $block = Tools::jsonDecode($obj->block);

        foreach (array('sourcepath', 'destinationpath') as $key) {
            if (isset($obj->$key)) {
                $block->$key = $obj->$key;
            } elseif (isset($block->$key)) {
                $obj->$key = $block->$key;
            }
        }

        $obj->block = Tools::jsonEncode($block);

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Description :'),
            'size' => 92,
            'id' => 'description',
            'name' => 'description',
        );

        $this->fields_form['input'][] = array(
            'type' => 'hidden',
            'id' => 'callback',
            'name' => 'callback',
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Cron Time :'),
            'size' => 92,
            'id' => 'crontime',
            'name' => 'crontime',
            'desc' => $this->l('From example: 00 1 * * * for each day at 1h am, or */15 * * * 1 for each quarter of the monday')
            .'<br />'.$this->l('Format: <Minute> <hour> <number of the day> <number of the month> <number of the day of week>'),
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Url :'),
            'size' => 92,
            'id' => 'sourcepath',
            'name' => 'sourcepath',
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Filename :'),
            'size' => 92,
            'id' => 'destinationpath',
            'name' => 'destinationpath',
            'desc' => $this->l('Let empty to use the original name.')
            .'<br />'.$this->l('You can use %date% variable to have a uniq name'),
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Channel :'),
            'size' => 92,
            'id' => 'channel',
            'name' => 'channel',
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Id Shop :'),
            'size' => 92,
            'id' => 'id_shop',
            'name' => 'id_shop',
        );

        $this->fields_form['submit'] = array(
                'title' => $this->l('Save'),
                'class' => 'button btn btn-default',
        );

        return parent::renderForm();
    }

    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        if (Tools::getIsset('uploader') || $obj->callback === 'AIUploader::httpUploader') {
            return $this->renderHttpUploadForm();
        }

        if (Tools::getIsset('ftpuploader') || $obj->callback === 'AIUploader::ftpUploader') {
            return $this->renderFtpUploadForm();
        }

        $obj->block = addslashes($obj->block);

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Description :'),
            'size' => 92,
            'id' => 'description',
            'name' => 'description',
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Cron Time :'),
            'size' => 92,
            'id' => 'crontime',
            'name' => 'crontime',
            'desc' => $this->l('From example: 00 1 * * * for each day at 1h am, or */15 * * * 1 for each quarter of the monday')
            .'<br />'.$this->l('Format: <Minute> <hour> <number of the day> <number of the month> <number of the day of week>'),
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Callback :'),
            'size' => 92,
            'id' => 'callback',
            'name' => 'callback',
        );

        $this->fields_form['input'][] = array(
            'type' => 'textarea',
            'label' => $this->l('Block (json format) :'),
            'rows' => 8,
            'cols' => 91,
            'id' => 'block',
            'name' => 'block',
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Channel :'),
            'size' => 92,
            'id' => 'channel',
            'name' => 'channel',
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Id Shop :'),
            'size' => 92,
            'id' => 'id_shop',
            'name' => 'id_shop',
        );

        $this->fields_form['submit'] = array(
                'title' => $this->l('Save'),
                'class' => 'button btn btn-default',
        );

        $this->tpl_form_vars = array('comment' => $obj);

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::getIsset('submitAddadvancedimporter_cron')) {
            if (Tools::getIsset('uploader') || Tools::getValue('callback') === 'AIUploader::httpUploader') {
                if (!($obj = $this->loadObject(true))) {
                    return;
                }

                $pathinfo = pathinfo(Tools::getValue('sourcepath'));
                $block = array(
                    'sourcepath' => Tools::getValue('sourcepath'),
                    'destinationpath' => Tools::getValue('destinationpath'),
                );

                $obj->block = Tools::jsonEncode($block);

                $this->copyFromPost($obj, $this->table);
                $this->validateRules();
                if (count($this->errors) <= 0) {
                    $obj->save();
                } else {
                    $this->display = 'edit';

                    return false;
                }

                return false;
            } elseif (Tools::getIsset('ftpuploader') || Tools::getValue('callback') === 'AIUploader::ftpUploader') {
                if (!($obj = $this->loadObject(true))) {
                    return;
                }

                $block = array(
                    'user' => Tools::getValue('user'),
                    'password' => Tools::getValue('password'),
                    'host' => Tools::getValue('host'),
                    'port' => Tools::getValue('port'),
                    'sourcedir' => Tools::getValue('sourcedir'),
                );

                $obj->block = Tools::jsonEncode($block);

                $this->copyFromPost($obj, $this->table);
                $this->validateRules();
                if (count($this->errors) <= 0) {
                    $obj->save();
                } else {
                    $this->display = 'edit';

                    return false;
                }

                return false;
            }
            /*else
            {
                if (!($obj = $this->loadObject(true)))
                    return;
                var_export($obj->block);
                $obj->block = Tools::getValue('block');
                var_export($obj->block);
                die();
            }*/
        }

        return parent::postProcess();
    }

    public function initToolbar()
    {
        if (!Tools::getIsset('id_advancedimporter_cron') && !Tools::getIsset('addadvancedimporter_cron')
            && Tools::getValue('callback') !== 'AIUploader::httpUploader'
        ) {
            $this->toolbar_btn['save-calendar'] = array(
                'href' => self::$currentIndex.'&addadvancedimporter_cron&uploader&token='.$this->token,
                'desc' => $this->l('Add HTTP uploader'),
            );
            $this->toolbar_btn['save-calendar2'] = array(
                'href' => self::$currentIndex.'&addadvancedimporter_cron&ftpuploader&token='.$this->token,
                'desc' => $this->l('Add FTP uploader'),
            );
        }

        return parent::initToolbar();
    }
}
