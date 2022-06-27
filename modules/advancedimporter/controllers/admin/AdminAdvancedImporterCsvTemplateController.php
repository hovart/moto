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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/csvtemplate.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/converter/csvconverter.php';

class AdminAdvancedImporterCsvTemplateController extends ModuleAdminController
{
    protected $colorOnBackground = true;
    protected $color_on_background = true; /* Ne sert que si un jour PS réutilise la norme */

    public function __construct()
    {
        $this->table = 'advancedimporter_csv_template';
        $this->className = 'CsvTemplate';

        $this->module = 'advancedimporter';
        $this->multishop_context = Shop::CONTEXT_ALL;

        $this->_orderBy = 'id_advancedimporter_csv_template';
        $this->_orderWay = 'DESC';

        $this->bootstrap = true;

        $this->fields_list = array(
            'id_advancedimporter_csv_template' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 30,
            ),
            'filepath' => array(
                'title' => $this->l('File path'),
                'align' => 'left',
            ),
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

        $this->informations[] = $this->l('CSV templates are made to translate any csv or excel files to the XML format supported by the module.');

        parent::__construct();
    }

    protected function getAssistantUrl()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        return $this->context->link->getAdminLink('AdminAdvancedImporterTemplateAssistant')
            .'&csv-template='.(int)$obj->id;
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
        }

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('File path:'),
            'size' => 92,
            'id' => 'filepath',
            'name' => 'filepath',
            'desc' => $this->l('The file path is used to determine the CSV file concerned by the template'),
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Root tag:'),
            'size' => 92,
            'id' => 'roottag',
            'name' => 'roottag',
            'desc' => $this->l('products, objects, ...'),
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Delimiter:'),
            'size' => 92,
            'id' => 'delimiter',
            'name' => 'delimiter',
            'desc' => $this->l('Generally ","'),
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Enclosure:'),
            'size' => 92,
            'id' => 'enclosure',
            'name' => 'enclosure',
            'desc' => $this->l('Generally \'"\''),
        );

        $this->fields_form['input'][] = array(
            'type' => 'text',
            'label' => $this->l('Encoding:'),
            'size' => 92,
            'id' => 'encoding',
            'name' => 'encoding',
            'desc' => $this->l('Generally \'UTF-8\''),
        );

        $this->fields_form['input'][] = array(
            'type' => 'switch',
            'label' => $this->l('Ignore first line:'),
            'size' => 92,
            'id' => 'ignore_first_line',
            'name' => 'ignore_first_line',
            'is_bool' => true,
            'values' => array(
                array(
                    'id' => 'active_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ),
                array(
                    'id' => 'active_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ),
            ),
            'desc' => $this->l('Does the first line contain title of the columns?'),
        );

        if (empty($obj->nodes)) {
            $this->fields_form['input'][] = array(
                'type' => 'switch',
                'label' => $this->l('Use advanced mode:'),
                'size' => 92,
                'id' => 'advanced_mode',
                'name' => 'advanced_mode',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ),
                    array(
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ),
                ),
                'desc' => $this->l('Do you whant to use the advanced mode?'),
            );

            $this->fields_form['input'][] = array(
                'type' => 'hidden',
                'label' => $this->l('Data:'),
                'size' => 92,
                'id' => 'data',
                'name' => 'data',
            );
        }

        $this->fields_form['input'][] = array(
            'type' => 'textarea',
            'label' => $this->l('Template :'),
            'rows' => 50,
            'cols' => 91,
            'id' => 'template',
            'name' => 'template',
            'desc' => $this->l('Use {{n}} where "n" is the column number. Use {{ if null n }}...{{end if}} if part have to be added only when column "n" is not empty.'),
        );

        if (empty($obj->nodes)) {
            $this->fields_form['input'][] = array(
                'type' => 'select',
                'label' => $this->l('Flow type:'),
                'id' => 'flow_type',
                'options' => array(
                    'query' => $this->getFlowsList(),
                    'id' => 'id',
                    'name' => 'name',
                ),
                'name' => 'flow_type',
            );

            $this->fields_form['input'][] = array(
                'id' => 'virtual_form',
                'name' => 'virtual_form',
                'type' => 'free',
                'label' => $this->l('Define the type of each column:'),
                'desc' => $this->l('Select attributes you want to import.').'<br />'
                .$this->l('For each inputs:').'<br />'
                .'<ul>'
                .'<li>'.$this->l('let empty if you dont want to use the attributes').'</li>'
                .'<li>'
                .$this->l('set the column number (1, 2, 3, ...) or letters (A, B, C, BS, ...) to use the column value')
                .'</li>'
                .'<li>'.$this->l('set value between double quote ("my constant value") to use a constant').'</li>'
                .'</ul>',
            );
            $this->fields_value['virtual_form'] = '<div class="easyCsvFormContainer"></div>
                <script type="text/javascript">
                    $(function() {
                        AIHideUnusedForm();
                        AIRefreshForm($("#data").val());
                    });
                </script>';
        }

        $this->fields_form['submit'] = array(
                'title' => $this->l('Save'),
                'class' => 'button btn btn-default',
        );

        $this->tpl_form_vars = array('comment' => $obj);

        return parent::renderForm();
    }

    public function displayAjax()
    {
        $flow_type = Tools::getValue('flow_type');
        $object_type = Tools::getValue('object_type');

        $converter = new CsvConverter($this->module, $flow_type, $object_type);

        if (Tools::getValue('external_reference')) {
            $converter->setExternalReference(Tools::getValue('external_reference'));
        }

        foreach (Tools::getValue('node', array()) as $key => $node) {
            if (!empty($node['remove'])) {
                continue;
            }
            $converter->addNode($node['name'], $node['values'], $node['condition']);
        }

        $new_entry_type = Tools::getValue('node_entry_type');
        if (!empty($new_entry_type)) {
            $converter->addNode($new_entry_type);
        }

        $return = array();
        $return['form'] = $converter->getForm();
        $return['data'] = http_build_query(array(
            'external_reference' => Tools::getValue('external_reference'),
            'node' => Tools::getValue('node'),
        ));
        if (!$return['data']) {
            $return['data'] = '';
        }
        $return['template'] = $converter->getTemplate();

        echo Tools::jsonEncode($return);
    }

    protected function getFlowsList()
    {
        $flows = array();
        $flows['product.product'] = array(
            'id' => 'product.product',
            'name' => 'products',
        );
        $flows['stock.'] = array(
            'id' => 'stock.',
            'name' => 'stocks',
        );
        $flows['associations.'] = array(
            'id' => 'associations.',
            'name' => 'associations',
        );

        $class_index = include _PS_CACHE_DIR_.'class_index.php';
        $class_list = array();
        foreach (array_keys($class_index) as $classname) {
            if (preg_match('/Core$/', $classname)) {
                continue;
            }
            if (preg_match('/Controller$/', $classname)) {
                continue;
            }
            if (preg_match('/^Abstract/', $classname)) {
                continue;
            }
            if (preg_match('/^Tree/', $classname)) {
                continue;
            }
            if (!is_subclass_of($classname, 'ObjectModel')) {
                continue;
            }
            if (!isset($classname::$definition['fields'])) {
                continue;
            }
            $class_list[] = $classname;
            $flows['object.'.$classname] = array(
                'id' => 'object.'.$classname,
                'name' => 'object "'.$classname.'"',
            );
            $flows['delete.'.$classname] = array(
                'id' => 'delete.'.$classname,
                'name' => 'delete "'.$classname.'"',
            );
        }

        ksort($flows);

        return $flows;
    }
}
