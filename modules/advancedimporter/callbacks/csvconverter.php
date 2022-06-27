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

class CsvConverter
{
    public static $id_advancedimporter_block;
    public static $id_advancedimporter_flow;
    public static $id_shop;

    public static function convertFromCollection($block)
    {
        require_once _PS_MODULE_DIR_.'advancedimporter/classes/csvtemplate.php';

        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('CsvTemplate');
        } else {
            $collection = new Collection('CsvTemplate');
        }

        foreach ($collection as $template) {
            // Parse all file matching the filepath
            $filepath = '';
            foreach (str_split($template->filepath) as $char) {
                if (ctype_alpha($char)) {
                    $filepath .= '['.Tools::strtolower($char).Tools::strtoupper($char).']';
                } else {
                    $filepath .= $char;
                }
            }
            $array = glob(_PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'.$filepath);
            if ($array !== false) {
                foreach ($array as $filename) {
                    self::createFile($template, $filename);
                }
            }
        }
    }

    public static function convert($block)
    {
        if (!isset($block->filepath)) {
            throw new Exception('Filepath is missing');
        }

        if (!isset($block->roottag)) {
            throw new Exception('Roottag is missing');
        }

        if (!isset($block->template)) {
            throw new Exception('Template is missing');
        }

        if (!isset($block->delimiter)) {
            $block->delimiter = ',';
        }

        if (!isset($block->enclosure)) {
            $block->enclosure = '"';
        }

        if (!isset($block->escape)) {
            $block->escape = '\\';
        }

        if (!isset($block->encoding)) {
            $block->encoding = 'UTF-8';
        }

        if (!isset($block->ignore_first_line)) {
            $block->ignore_first_line = true;
        }

        // Parse all file matching the filepath
        $filepath = '';
        foreach (str_split($block->filepath) as $char) {
            if (ctype_alpha($char)) {
                $filepath .= '['.Tools::strtolower($char).Tools::strtoupper($char).']';
            } else {
                $filepath .= $char;
            }
            $filepath .= '['.Tools::strtolower($char).Tools::strtoupper($char).']';
        }
        foreach (glob(_PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'.$filepath) as $filename) {
            self::createFile($block, $filename);
        }
    }

    public static function createFile($block, $filename)
    {
        // Manage CR line break
        ini_set('auto_detect_line_endings', true);

        $first_line = true;
        $items = array();

        $flow = Flow::getByPath($filename);

        if (($handle = fopen($filename, 'r')) !== false) {
            while (($line = fgetcsv($handle, 0, $block->delimiter, $block->enclosure, $block->escape)) !== false) {
                if ($block->ignore_first_line && $first_line) {
                    $first_line = false;
                    continue;
                }
                $item = $block->template;
                $item = str_replace('###double-quote###', '"', $block->template);

                // Replace 5 level of if_not_null included
                for ($i = 0; $i <= 5; $i++) {
                    $item = preg_replace_callback(
                        '/<csv_if_not_null column="(\d+)">(.*)<\/csv_if_not_null>/Usi',
                        function ($matches) use ($line) {
                            $row = isset($line[$matches[1]]) ? trim($line[$matches[1]]) : '';
                            if (Tools::strlen($row) === 0) {
                                return '';
                            } else {
                                return $matches[2];
                            }
                        },
                        $item
                    );
                    $item = preg_replace_callback(
                        '/<csv_if_null column="(\d+)">(.*)<\/csv_if_null>/Usi',
                        function ($matches) use ($line) {
                            $row = isset($line[$matches[1]]) ? trim($line[$matches[1]]) : '';
                            if (Tools::strlen($row) > 0) {
                                return '';
                            } else {
                                return $matches[2];
                            }
                        },
                        $item
                    );
                }

                $item = preg_replace('/<\/csv_if_not_null>/Usi', '', $item);
                $item = preg_replace('/<\/csv_if_null>/Usi', '', $item);

                $item = preg_replace_callback(
                    '/\{\{(\d+)\}\}/Usi',
                    function ($matches) use ($line, $block) {
                        $row = isset($line[$matches[1]]) ? trim($line[$matches[1]]) : '';
                        if (Tools::strlen($row) === 0) {
                            return '';
                        } else {
                            if (defined('ENT_XML1')) {
                                return htmlspecialchars(
                                    self::utf8ForXml($row),
                                    ENT_XML1 | ENT_IGNORE,
                                    $block->encoding
                                );
                            } else {
                                // Case PHP 5.3
                                return htmlspecialchars(self::utf8ForXml($row), ENT_IGNORE, $block->encoding);
                            }
                        }
                    },
                    $item
                );

                $items[] = $item;
            }
            fclose($handle);
        }

        $id = uniqid();

        rename($filename, _PS_MODULE_DIR_.'advancedimporter/flows/import/imported/'.basename($filename));
        $flow->path = str_replace(_PS_MODULE_DIR_.'advancedimporter/flows/import/', '', $filename).'.'.$id.'.xml';
        $flow->filename = $flow->filename.'.'.$id.'.xml';
        $flow->type = 'xml';
        $flow->save();

        file_put_contents(
            $filename.'.'.$id.'.xml.tmp',
            "<?xml version=\"1.0\" encoding=\"{$block->encoding}\"?>"
            ."<{$block->roottag}>".implode('', $items)."</{$block->roottag}>"
        );
        rename($filename.'.'.$id.'.xml.tmp', $filename.'.'.$id.'.xml');
        unlink(_PS_MODULE_DIR_.'advancedimporter/flows/import/imported/'.basename($filename));
    }

    protected static function utf8ForXml($string)
    {
            return preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
    }
}
