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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/converter/objectconverterabstract.php';

class CsvConverter extends ObjectConverterAbstract
{
    public function getTemplate()
    {
        $xml = new SimpleXmlElement('<'.$this->flow_type.' />');
        switch ($this->flow_type) {
            case 'object':
            case 'delete':
                $xml->addAttribute('type', $this->object_type);
                break;
            default:
                break;
        }

        if (!empty($this->external_reference)) {
            $xml->addAttribute('external-reference', '{{'.self::convertColumnLetters($this->external_reference).'}}');
        }

        foreach ($this->used_nodes as $nodeData) {
            $node = $this->nodes[$nodeData['node']];
            $this->addChild($xml, $node['schema'], $nodeData, $node['name']);
        }

        return preg_replace('/^<\?xml.*\?>\n/Usi', '', $xml->asXml());
    }

    public function addChild($xml, $schema, $nodeData, $nodeName, $depth = 0)
    {
        $value = null;
        if ($schema['value'] && !empty($nodeData['values']['node'][$schema['identifier']])) {
            $value = $this->getValue($nodeData['values']['node'][$schema['identifier']]);
        } elseif (!empty($schema['default-value'])) {
            $value = $schema['default-value'];
        }

        if (!empty($nodeData['condition']) && $depth === 0) {
            $condition = $xml->addChild('csv_if_not_null');
            $condition->addAttribute('column', self::convertColumnLetters($nodeData['condition'], false));
            $child = $condition->addChild($nodeName, $value);
        } else {
            $child = $xml->addChild($nodeName, $value);
        }

        if (isset($schema['attributes'])) {
            foreach ($schema['attributes'] as $attribute) {
                if (!empty($nodeData['values']['attributes'][$attribute])) {
                    $child->addAttribute($attribute, $this->getValue($nodeData['values']['attributes'][$attribute]));
                }
            }
        }

        if (isset($schema['children'])) {
            foreach ($schema['children'] as $name => $schema) {
                $this->addChild($child, $schema, $nodeData, $name, $depth + 1);
            }
        }
    }

    public static function convertColumnLetters($letters)
    {
        if (is_numeric($letters)) {
            return $letters - 1;
        }

        $numeric = 0;
        $digit = 0;
        foreach (array_reverse(str_split($letters)) as $letter) {
            $numeric += (ord(Tools::strtolower($letter)) - 96) * pow(26, $digit);
            ++$digit;
        }

        return $numeric - 1;
    }

    protected function getValue($value)
    {
        $value = preg_replace('/^(\d+)$/', '{{$1}}', $value);
        $value = preg_replace_callback(
            '/^(\w{0,2})$/Usi',
            function ($matches) {
                return '{{'.CsvConverter::convertColumnLetters($matches[1]).'}}';
            },
            $value
        );
        $value = preg_replace('/^"(.+)"$/', '$1', $value);

        return $value;
    }

    public function getForm()
    {
        $html = array();
        $html[] = '<div>';
        $html[] = '<div class="form-group">';
        $html[] = '<div class="col-lg-12">';
        $html[] = '<select class="easyCsvForm" name="node_entry_type">';
        $html[] = '<option value="">'.$this->module->l('Add entry').'</option>';
        foreach ($this->getAvailableNodes() as $key => $node) {
            $html[] = '<option value="'.$key.'">'.$node['description'].'</option>';
        }
        $html[] = '</select>';
        $html[] = '</div>';
        $html[] = '</div>';

        $html[] = '<div class="form-group">';
        $html[] = '<label class="control-label col-lg-2">';
        $html[] = $this->module->l('External reference:');
        $html[] = '</label>';
        $html[] = '<div class="text-right col-lg-10">';
        $html[] = '<input class="easyCsvForm" type="text" name="external_reference" value="'
            .$this->external_reference.'" />';
        $html[] = '</div>';
        $html[] = '</div>';

        foreach ($this->used_nodes as $key => $node) {
            $name = $node['node'];
            $schema = $this->nodes[$name];
            $html[] = '<div class="form-group">';
            $html[] = '<strong class="text-uppercase control-label col-lg-2">'
                .$schema['description'].'</strong>';
            $html[] = '<input class="easyCsvForm" type="hidden" name="node['
                .$key.'][name]" value="'.$name.'" />';
            $html[] = '<div class="text-right col-lg-10">';
            $html[] = '<input type="button" class="easyCsvFormRemove" name="node['
                .$key.'][remove]" value="'.$this->module->l('Remove').'" />';
            $html[] = '</div>';
            $html[] = '</div>';
            $html[] = '<div class="form-group">';
            $html[] = '<label class="control-label col-lg-2">'.$this->module->l('Condition:').'</label>';
            $html[] = '<div class="col-lg-10">';
            $html[] = '<input class="easyCsvForm" type="text" name="node['.$key.'][condition]" value="'
                .$node['condition'].'" />';
            $html[] = '<p class="help-block">';
            $html[] = $this->module->l(
                'Don\'t use this option if the next column is empty. Let empty to disable the condition'
            );
            $html[] = '</p>';
            $html[] = '</div>';
            $html[] = '</div>';
            $html[] = '<div class="form-group">';
            $this->addFormValues($key, $node, $schema['schema'], $html);
            $html[] = '</div>';
        }
        $html[] = '</div>';

        return implode('', $html);
    }

    public function addFormValues($key, $node, $schema, &$html)
    {
        if ($schema['value']) {
            $html[] = '<label class="control-label col-lg-2">'.$schema['description'].'</label>';
            $html[] = '<div class="col-lg-10">';
            $html[] = '<input class="easyCsvForm" type="text" name="node['.$key.'][values][node]['.
                $schema['identifier'].']" value="'.
                htmlspecialchars($node['values']['node'][$schema['identifier']]).'" />';
            $html[] = '</div>';
        }

        if (isset($schema['attributes'])) {
            foreach ($schema['attributes'] as $attribute) {
                $html[] = '<label  class="control-label col-lg-2">'
                    .sprintf($this->module->l('Attribute "%s":'), $attribute).'</label>';
                $html[] = '<div class="col-lg-10">';
                $html[] = '<input class="easyCsvForm" type="text" name="node['
                    .$key.'][values][attributes]['.$attribute.']" value="'
                    .htmlspecialchars($node['values']['attributes'][$attribute]).'" />';
                $html[] = '</div>';
            }
        }
        if (isset($schema['children'])) {
            foreach ($schema['children'] as $child) {
                $this->addFormValues($key, $node, $child, $html);
            }
        }

        return $this;
    }
}
