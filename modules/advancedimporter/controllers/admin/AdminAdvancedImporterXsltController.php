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

class AdminAdvancedImporterXsltController extends ModuleAdminController
{
    protected $colorOnBackground = true;
    protected $color_on_background = true; /* Ne sert que si un jour PS réutilise la norme */

    public function __construct()
    {
        $this->table = 'advancedimporter_xslt';
        $this->className = 'Xslt';

        $this->module = 'advancedimporter';
        $this->multishop_context = Shop::CONTEXT_ALL;

        $this->_orderBy = 'id_advancedimporter_xslt';
        $this->_orderWay = 'DESC';

        $this->bootstrap = true;

        $this->fields_list = array(
            'id_advancedimporter_xslt' => array('title' => $this->l('ID'), 'align' => 'center', 'width' => 30),
            'xpath_query' => array('title' => $this->l('XPath Query matching to the xml'), 'align' => 'left'),
        );

        $this->addRowAction('add');
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete'),
                'confirm' => $this->l('Are you sure?'),
            ),
        );

        if (!class_exists('XSLTProcessor')) {
            $this->warnings[] =
                $this->l('The library libxml is not installed. To use XSLT you must install this PHP extension.');
        }

        $this->informations[] = $this->l('XSLT are made to translate any XML to the format supported by the module.');

        parent::__construct();
    }

    protected function getAssistantUrl()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        return $this->context->link->getAdminLink('AdminAdvancedImporterTemplateAssistant').'&xslt='.(int)$obj->id;
    }

    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        if (!empty($obj->nodes)) {
            $this->informations[] = '<a href="'.$this->getAssistantUrl().'">'
                .$this->l('Edit with the assistant')
                .'</a>';
        } else {
            $link = '<a href="http://xslt.prestashopxmlimporter.madef.fr">'.$this->l('online tool').'</a>';
            $this->informations[] = sprintf($this->l('Construct your own XLST for flow product using our %s.'), $link);
        }

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('XPath Query :'),
            'size' => 92,
            'id' => 'xpath_query',
            'name' => 'xpath_query',
            'desc' => $this->l('The XPath Query is used to determine the XML to convert'),
        );

        if (empty($obj->nodes)) {
            $this->fields_form['input'][] = array(
                'type' => 'switch',
                'label' => $this->l('Use Smarty TPL:'),
                'id' => 'use_tpl',
                'is_bool'   => true,
                'name' => 'use_tpl',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->l('On'),
                    ),
                    array(
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->l('Off'),
                    ),
                ),
                'desc' => $this->l('By default the template must be an XSLT, but you can use Smarty template instead')
                    .'<br />'
                    .$this->l('vars: $root a SimpleXmlElement of the xml to convert'),
            );
        }

        $this->fields_form['input'][] = array(
            'type' => 'textarea',
            'label' => $this->l('XSLT :'),
            'rows' => 50,
            'cols' => 91,
            'id' => 'xml',
            'name' => 'xml',
            'desc' => $this->l('XSLT definition used to translate XML files into compatible XML files for import'),
        );

        $this->fields_form['submit'] = array(
                'title' => $this->l('Save'),
                'class' => 'button btn btn-default',
        );

        $this->tpl_form_vars = array('comment' => $obj);

        return parent::renderForm();
    }
}
