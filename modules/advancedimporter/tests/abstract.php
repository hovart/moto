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

abstract class TestAbstract extends PHPUnit_Framework_TestCase
{
    protected function checkErrors()
    {
        $sql = 'select count(*) as count, concat(message) as messages from `'._DB_PREFIX_.'advancedimporter_log` where type = "error"';
        $row = Db::getInstance()->getRow($sql);
        if ($row['count'] > 0) {
            throw new Exception('There are errors in the log: '.$row['messages']);
        }
    }

    protected function cleanDb()
    {
        foreach (array('advancedimporter_flow', 'advancedimporter_block', 'advancedimporter_log') as $table) {
            $sql = 'DELETE FROM `'._DB_PREFIX_.bqSql($table).'`';
            Db::getInstance()->execute($sql);
        }
    }

    protected function import($type)
    {
        switch ($type) {
            case 'product':
                $callback = 'FlowsImporter::products';
                break;
            case 'object':
                $callback = 'FlowsImporter::objects';
                break;
            case 'association':
                $callback = 'FlowsImporter::associations';
                break;
            case 'stock':
                $callback = 'FlowsImporter::stocks';
                break;
            case 'delete':
                $callback = 'FlowsImporter::deletes';
                break;
        }

        $block = new Block();
        $block->id_shop = 1;
        $block->callback = $callback;
        $block->channel = 1;
        $block->id_advancedimporter_flow = 0;
        $created_at = new DateTime();
        $block->created_at = $created_at->format('Y-m-d H:i:s');
        $block->save();
    }

    protected function cleanRef($ref, $type)
    {
        try {
            $external_reference = ExternalReference::getByExternalReference($ref, $type);
        } catch (Exception $e) {
            return;
        }

        $id = $external_reference->id_object;

        // Reset data
        $object = new $type($id);
        $object->delete();

        $external_reference->delete();
    }

    protected function execBlocks()
    {
        do {
            $block = Block::getNextBlock(1);

            if (!$block) {
                break;
            }

            $block->run(1);
        } while (true);
    }

    protected function cleanQueue()
    {
        $files = glob(_PS_MODULE_DIR_.'advancedimporter/flows/import/queue/*.xml');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        } // delete file
    }

    protected function copy($file)
    {
        $file_path = _PS_MODULE_DIR_.'advancedimporter/tests/files/'.$file;
        $destination_path = _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'.$file;
        Tools::copy($file_path, $destination_path);
    }

    protected function getLastModifiedObject($table, $type)
    {
        switch ($table) {
            case 'tax_rules_group':
                $sql = 'SELECT max(id_'.bqSql($table).') FROM `'._DB_PREFIX_.bqSql($table).'`';
                break;
            default:
                $sql = 'SELECT id_'.bqSql($table).' FROM `'._DB_PREFIX_.bqSql($table).'` order by date_upd desc';
        }
        $object_id = Db::getInstance()->getValue($sql);

        return new $type($object_id);
    }
}
