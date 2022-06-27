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

class ImporterCollection implements Iterator
{
    protected static $instance = null;
    protected $collection;
    protected $position = 0;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new ImporterCollection();
        }

        return self::$instance;
    }

    protected function __construct()
    {
        $this->collection = array();
        foreach (glob(_PS_MODULE_DIR_.'advancedimporter/importer/*.php') as $file) {
            $class_name = preg_replace('/^.*\/([a-z]+)\.php/Usi', '$1', $file);
            if ($class_name === 'index') {
                continue;
            }

            include_once($file);

            if (!class_exists($class_name) || !is_subclass_of($class_name, 'XmlImportFlowsAbstract')) {
                continue;
            }

            $this->collection[] = $class_name;
        }
    }

    public function current()
    {
        return $this->collection[$this->position];
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function next()
    {
        ++$this->position;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->collection[$this->position]);
    }
}
