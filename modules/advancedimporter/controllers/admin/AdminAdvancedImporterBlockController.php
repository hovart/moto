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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/block.php';

class AdminAdvancedImporterBlockController extends ModuleAdminController
{
    protected $colorOnBackground = true;
    protected $color_on_background = true; /* Ne sert que si un jour PS réutilise la norme */

    public function __construct()
    {
        $this->table = 'advancedimporter_block';
        $this->className = 'Block';

        $this->module = 'advancedimporter';
        $this->multishop_context = Shop::CONTEXT_ALL;

        $this->_orderBy = 'id_advancedimporter_block';
        $this->_orderWay = 'DESC';
        $this->bootstrap = true;

        $this->addRowAction('view');
        $this->addRowAction('run');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete'),
                'confirm' => $this->l('Are you sure?'),
            ),
        );

        $result_list = array(
            '0' => $this->l('Waiting'),
            '1' => $this->l('Running'),
            '-1' => $this->l('Error'),
            '2' => $this->l('Success'),
        );
        $this->fields_list = array(
            'id_advancedimporter_block' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 30,
            ),
            'id_advancedimporter_flow' => array(
                'title' => $this->l('Flow ID'),
                'align' => 'center',
                'width' => 30,
            ),
            'created_at' => array(
                'title' => $this->l('Created at'),
                'align' => 'center',
                'width' => 120,
            ),
            'planned_at' => array(
                'title' => $this->l('Planned at'),
                'align' => 'center',
                'width' => 120,
            ),
            'treatment_start' => array(
                'title' => $this->l('Started at'),
                'width' => 120,
                'align' => 'center',
            ),
            'treatment_end' => array(
                'title' => $this->l('Ended at'),
                'width' => 120,
                'align' => 'center',
            ),
            'callback' => array(
                'title' => $this->l('Callback'),
                'align' => 'left',
            ),
            'result' => array(
                'title' => $this->l('Result'),
                'width' => 80,
                'align' => 'center',
                'type' => 'select',
                'list' => $result_list,
                'filter_key' => 'result',
            ),
            'memory' => array(
                'title' => $this->l('Memory'),
                'width' => 20,
                'align' => 'right',
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
        $obj->run();
        Block::writeCache();

        return Tools::redirectAdmin(
            '?controller=AdminAdvancedImporterBlock&token='.$this->token
        );
    }

    /**
     * Custom action icon "run".
     */
    public function displayRunLink($token = null, $id = null)
    {
        if (!array_key_exists('run', self::$cache_lang)) {
            self::$cache_lang['run'] = $this->l('Run block');
        }

        $this->context->smarty->assign(array(
            'module_dir' => __PS_BASE_URI__.'modules/advancedimporter/',
            'href' => self::$currentIndex.
                '&'.$this->identifier.'='.$id.
                '&run=1&token='.($token != null ? $token : $this->token),
            'action' => self::$cache_lang['run'],
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

    public function renderView()
    {
        return $this->renderForm();
    }

    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        if ($obj->result == 0) {
            $obj->result = $this->l('Not executed');
        } elseif ($obj->result == -1) {
            $obj->result = $this->l('Error durring execution');
        } else {
            $obj->result = $this->l('Success');
        }

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Callback:'),
            'size' => 92,
            'id' => 'callback',
            'name' => 'callback', );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Channel:'),
            'size' => 5,
            'id' => 'channel',
            'name' => 'channel', );

        $this->fields_form['input'][] = array(
            'type' => 'textarea',
            'readonly' => true,
            'label' => $this->l('Block:'),
            'size' => 92,
            'id' => 'block',
            'name' => 'block', );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Result:'),
            'size' => 20,
            'id' => 'result',
            'name' => 'result', );

        $this->fields_form['input'][] = array(
            'type' => 'textarea',
            'readonly' => true,
            'label' => $this->l('Error:'),
            'size' => 92,
            'id' => 'error',
            'name' => 'error', );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Memory:'),
            'size' => 92,
            'id' => 'memory',
            'name' => 'memory', );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Parent ID:'),
            'size' => 92,
            'id' => 'id_parent',
            'name' => 'id_parent', );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Created at:'),
            'size' => 20,
            'id' => 'created_at',
            'name' => 'created_at', );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Plannified at:'),
            'size' => 20,
            'id' => 'planned_at',
            'name' => 'planned_at', );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Treatment start:'),
            'size' => 20,
            'id' => 'treatment_start',
            'name' => 'treatment_start', );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Treatment end:'),
            'size' => 20,
            'id' => 'treatment_end',
            'name' => 'treatment_end', );

        unset($this->toolbar_btn['save']);
        unset($this->toolbar_btn['cancel']);
        $this->toolbar_btn['back'] = array(
            'href' => $this->context->link->getAdminLink('AdminAdvancedImporterBlock'),
            'desc' => $this->l('Back to list'),
        );

        return parent::renderForm();
    }

    /**
     * AdminController::getList() override.
     *
     * @see AdminController::getList()
     */
    public function getList(
        $id_lang,
        $order_by = null,
        $order_way = null,
        $start = 0,
        $limit = null,
        $id_lang_shop = false
    ) {
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        foreach ($this->_list as &$item) {
            switch ($item['result']) {
                case -1:
                    $item['result'] = $this->l('Error');
                    break;
                case 0:
                    $item['result'] = $this->l('Waiting');
                    break;
                case 1:
                    $item['result'] = $this->l('Running');
                    break;
                case 2:
                    $item['result'] = $this->l('Success');
                    break;
            }
        }
    }

    public function postProcess()
    {
        switch (Tools::getValue('customaction')) {
            case 'delete':
                $this->deleteAll();
                break;
            default:
                break;
        }

        return parent::postProcess();
    }

    public function deleteAll()
    {
        Db::getInstance()->execute('truncate `'._DB_PREFIX_.$this->table.'`');
    }

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);

        $this->toolbar_btn['delete'] = array(
            'href' => $this->context->link->getAdminLink('AdminAdvancedImporterBlock').'&customaction=delete',
            'desc' => $this->l('Delete all'),
        );
    }
}
