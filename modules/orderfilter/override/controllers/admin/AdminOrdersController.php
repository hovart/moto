<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class AdminOrdersController extends AdminOrdersControllerCore
{
 
    public function __construct()
    {
        $this->table = 'order';
        $this->className = 'Order';
        $this->lang = false;
        $this->addRowAction('view');
        $this->explicitSelect = true;
        $this->allow_export = true;
        $this->deleted = false;
        $this->context = Context::getContext();
 
        $this->_select = '
        a.id_currency,
        a.id_order AS id_pdf,
        CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
        osl.`name` AS `osname`,
        os.`color`,
        (SELECT GROUP_CONCAT(odd.product_name SEPARATOR ", ") FROM `'._DB_PREFIX_.'order_detail` odd WHERE odd.id_order = a.id_order) as products,
        IF((SELECT COUNT(so.id_order) FROM `'._DB_PREFIX_.'orders` so WHERE so.id_customer = a.id_customer) > 1, 0, 1) as new';
 
 
        $this->_join = '
        LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = a.`id_customer`)
        LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = a.`current_state`)
        LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$this->context->language->id.')';
        $this->_orderBy = 'id_order';
        $this->_orderWay = 'DESC';
 
        $statuses_array = array();
        $statuses = OrderState::getOrderStates((int)$this->context->language->id);
 
        foreach ($statuses as $status)
            $statuses_array[$status['id_order_state']] = $status['name'];
 
 
 
        $this->fields_list = array(
        'id_order' => array(
            'title' => $this->l('ID'),
            'align' => 'center',
            'width' => 25
        ),
        'reference' => array(
            'title' => $this->l('Reference'),
            'align' => 'center',
            'width' => 65
        ),
        'new' => array(
            'title' => $this->l('New'),
            'width' => 25,
            'align' => 'center',
            'type' => 'bool',
            'tmpTableFilter' => true,
            'icon' => array(
                0 => 'blank.gif',
                1 => array(
                    'src' => 'note.png',
                    'alt' => $this->l('First customer order'),
                )
            ),
            'orderby' => false
        ),
        'customer' => array(
            'title' => $this->l('Customer'),
            'havingFilter' => true,
        ),
        'products' => array(
            'title' => $this->l('Products'),
            'havingFilter' => true,
            'filter_key' => 'products',
            'width' => 370,
        ),  
        'total_paid_tax_incl' => array(
            'title' => $this->l('Total'),
            'width' => 70,
            'align' => 'right',
            'prefix' => '<b>',
            'suffix' => '</b>',
            'type' => 'price',
            'currency' => true
        ),
        'payment' => array(
            'title' => $this->l('Payment: '),
            'width' => 100
        ),
        'osname' => array(
            'title' => $this->l('Status'),
            'color' => 'color',
            'width' => 280,
            'type' => 'select',
            'list' => $statuses_array,
            'filter_key' => 'os!id_order_state',
            'filter_type' => 'int',
            'order_key' => 'osname'
        ),
        'date_add' => array(
            'title' => $this->l('Date'),
            'width' => 130,
            'align' => 'right',
            'type' => 'datetime',
            'filter_key' => 'a!date_add'
        ),
        'id_pdf' => array(
            'title' => $this->l('PDF'),
            'width' => 35,
            'align' => 'center',
            'callback' => 'printPDFIcons',
            'orderby' => false,
            'search' => false,
            'remove_onclick' => true)
        );
 
        $this->shopLinkType = 'shop';
        $this->shopShareDatas = Shop::SHARE_ORDER;
 
        if (Tools::isSubmit('id_order'))
        {
            // Save context (in order to apply cart rule)
            $order = new Order((int)Tools::getValue('id_order'));
            if (!Validate::isLoadedObject($order))
                throw new PrestaShopException('Cannot load Order object');
            $this->context->cart = new Cart($order->id_cart);
            $this->context->customer = new Customer($order->id_customer);
        }
 
        AdminController::__construct();
    }
}