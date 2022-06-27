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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/converter/enrich/product.php';

abstract class ObjectConverterAbstract
{
    protected $module;
    protected $object_type;
    protected $flow_type;
    protected $nodes = array();
    protected $used_nodes = array();
    protected $external_reference = '';

    public function __construct($module, $flow_type, $object_type)
    {
        $this->module = $module;
        $this->object_type = $object_type;
        $this->flow_type = $flow_type;
        $this->loadNodes();
    }

    public function getClassList()
    {
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
        }

        return $class_list;
    }

    public function getAvailableNodes()
    {
        $nodes = array();

        $used_nodes = array();
        foreach ($this->used_nodes as $node) {
            $used_nodes[] = $node['node'];
        }

        foreach ($this->nodes as $key => $node) {
            if ($node['schema']['uniq']
                && in_array($key, $used_nodes)) {
                continue;
            }

            $nodes[$key] = $node;
        }

        uasort($nodes, function ($a, $b) {
            if ($a['description'] == $b['description']) {
                return 0;
            }

            return ($a['description'] < $b['description']) ? -1 : 1;
        });

        return $nodes;
    }

    public function getNodesAttributes()
    {
        $attributes = array();
        foreach ($this->nodes as $key => $value) {
            $attributes[$key] = $this->getAttributes($value['schema']);
        }
        return $attributes;
    }

    protected function getAttributes($node)
    {
        $attributes = array();
        if (!empty($node['attributes'])) {
            foreach ($node['attributes'] as $value) {
                $attributes[] = $value;
            }
        }

        if (isset($node['children'])) {
            foreach ($node['children'] as $child) {
                $attributes = array_merge($attributes, $this->getAttributes($child));
            }
        }

        return $attributes;
    }

    public function getNodesIdentifiers()
    {
        $identifiers = array();
        foreach ($this->nodes as $key => $value) {
            $identifiers[$key] = $this->getIdentifiers($value['schema']);
        }
        return $identifiers;
    }

    protected function getIdentifiers($node)
    {
        $identifiers = array();
        if (!empty($node['identifier'])) {
            $identifiers[] = $node['identifier'];
        }

        if (isset($node['children'])) {
            foreach ($node['children'] as $child) {
                $identifiers = array_merge($identifiers, $this->getIdentifiers($child));
            }
        }

        return $identifiers;
    }

    public function setExternalReference($reference)
    {
        $this->external_reference = $reference;

        return $this;
    }

    protected function loadNodes()
    {
        // Load form object
        $classname = Tools::ucfirst($this->object_type);

        if (!empty($classname)) {
            foreach ($classname::$definition['fields'] as $name => $data) {
                $node = array(
                    'name' => $name,
                    'schema' => array(
                        'attributes' => array(),
                        'children' => array(),
                        'value' => true,
                        'uniq' => true,
                        'identifier' => 'attribute/'.$name,
                        'description' => $this->module->l('Value:'),
                    ),
                    'description' => sprintf($this->module->l('Attribute "%s"'), $name),
                );
                if (isset($data['lang']) && $data['lang']) {
                    $node['schema']['attributes'][] = 'lang';
                }

                $this->nodes[$name] = $node;
            }
        }

        // Enrich with object
        $enrichClassName = 'EnrichConverter'
            .Tools::ucfirst($this->flow_type).Tools::ucfirst(!empty($this->object_type) ? $this->object_type : '');

        if (class_exists($enrichClassName)) {
            new $enrichClassName($this->nodes, $this->module);
        }

        return $this;
    }

    /**
     * @param string $node   Name of the node
     * @param array  $values Values of the node (contant, attribute "separator", ...)
     */
    public function addNode($node, $values = null, $condition = false)
    {
        $this->used_nodes[] = array(
            'node' => $node,
            'values' => $values,
            'condition' => $condition,
        );
    }

    abstract public function getForm();

    abstract public function getTemplate();
}
