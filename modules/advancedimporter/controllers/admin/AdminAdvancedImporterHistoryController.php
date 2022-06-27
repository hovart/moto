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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/history.php';

class AdminAdvancedImporterHistoryController extends ModuleAdminController
{
    protected $list_no_link = true;
    protected $colorOnBackground = true;
    protected $color_on_background = true; /* Ne sert que si un jour PS réutilise la norme */

    public function __construct()
    {
        $this->table = 'advancedimporter_history';
        $this->className = 'History';

        $this->module = 'advancedimporter';
        $this->multishop_context = Shop::CONTEXT_ALL;

        $this->_orderBy = 'date_add';
        $this->_orderWay = 'DESC';

        $this->bootstrap = true;

        $this->fields_list = array(
            'object_type' => array(
                'title' => $this->l('Object Type'),
                'align' => 'left',
                'width' => 100,
            ),
            'object_id' => array(
                'title' => $this->l('Object ID'),
                'align' => 'left',
                'width' => 50,
            ),
            'object_external_reference' => array(
                'title' => $this->l('External Reference'),
                'align' => 'left',
                'width' => 100,
            ),
            'flow_type' => array(
                'title' => $this->l('Flow type'),
                'align' => 'left',
                'width' => 50,
            ),
            'id_advancedimporter_flow' => array(
                'title' => $this->l('Flow ID'),
                'width' => 30,
                'align' => 'left',
            ),
            'id_advancedimporter_block' => array(
                'title' => $this->l('Block ID'),
                'width' => 30,
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

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }
}
