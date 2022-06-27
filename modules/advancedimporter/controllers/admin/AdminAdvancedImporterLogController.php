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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/log.php';

class AdminAdvancedImporterLogController extends ModuleAdminController
{
    protected $list_no_link = true;
    protected $colorOnBackground = true;
    protected $color_on_background = true; /* Ne sert que si un jour PS réutilise la norme */

    public function __construct()
    {
        $this->table = 'advancedimporter_log';
        $this->className = 'Log';

        $this->module = 'advancedimporter';
        $this->multishop_context = Shop::CONTEXT_ALL;

        $this->_orderBy = 'id_advancedimporter_log';
        $this->_orderWay = 'DESC';

        $this->bootstrap = true;

        $this->addRowAction('view');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete'),
                'confirm' => $this->l('Are you sure?'),
            ),
        );

        $type_list = array(
            'error' => $this->l('Error'),
            'sys' => $this->l('System'),
            'notice' => $this->l('Notice'),
            'success' => $this->l('success'),
        );
        $this->fields_list = array(
            'id_advancedimporter_log' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 30,
            ),
            'id_advancedimporter_flow' => array(
                'title' => $this->l('ID flow'),
                'align' => 'center',
                'width' => 30,
            ),
            'id_advancedimporter_block' => array(
                'title' => $this->l('ID block'),
                'align' => 'center',
                'width' => 30,
            ),
            'type' => array(
                'title' => $this->l('Type'),
                'width' => 120,
                'align' => 'left',
                'type' => 'select',
                'list' => $type_list,
                'filter_key' => 'type',
            ),
            'message' => array(
                'title' => $this->l('Message'),
                'align' => 'left',
            ),
            'date_add' => array(
                'title' => $this->l('Date'),
                'align' => 'center',
                'width' => 120,
            ),
        );

        parent::__construct();
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
        $this->toolbar_btn['delete'] = array(
            'href' => $this->context->link->getAdminLink('AdminAdvancedImporterLog').'&customaction=delete',
            'desc' => $this->l('Delete all'),
        );

        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }

    public function renderView()
    {
        return $this->renderForm();
    }

    public function renderForm()
    {
        if (!($this->loadObject(true))) {
            return;
        }

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('ID:'),
            'size' => 92,
            'id' => 'id',
            'name' => 'id', );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Block ID:'),
            'size' => 92,
            'id' => 'id_advancedimporter_block',
            'name' => 'id_advancedimporter_block', );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Type:'),
            'size' => 92,
            'id' => 'type',
            'name' => 'type', );

        $this->fields_form['input'][] = array(
            'type' => 'textarea',
            'readonly' => true,
            'label' => $this->l('Message:'),
            'size' => 92,
            'id' => 'message',
            'name' => 'message', );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'readonly' => true,
            'label' => $this->l('Date:'),
            'size' => 20,
            'id' => 'date_add',
            'name' => 'date_add', );

        unset($this->toolbar_btn['save']);
        unset($this->toolbar_btn['cancel']);

        $this->toolbar_btn['back'] = array(
            'href' => $this->context->link->getAdminLink('AdminAdvancedImporterLog'),
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
        parent::getList(
            $id_lang,
            $order_by,
            $order_way,
            $start,
            $limit,
            $id_lang_shop
        );

        foreach ($this->_list as &$item) {
            if ($item['type'] == 'notice') {
                $item['color'] = '#ffeec4';
            }

            if ($item['type'] == 'error') {
                $item['color'] = '#ffc6c6';
            }

            if ($item['type'] == 'success') {
                $item['color'] = '#d5ffd5';
            }
        }
    }
}
