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

class AIXmlFile
{
    protected static $files = array();
    protected static $xslt_collection = null;
    protected $file;
    protected $xml;

    public static function getInstance($file)
    {
        if (!isset(self::$files[$file])) {
            $content = Tools::file_get_contents(
                _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'.$file
            );
            self::$files[$file] = new AIXmlFile($file, $content);
        }

        return self::$files[$file];
    }

    public static function getInstanceFromXml($xml)
    {
        $key = md5($xml);
        if (!isset(self::$files[$key])) {
            self::$files[$key] = new AIXmlFile($key, $xml);
        }

        return self::$files[$key];
    }

    protected function __construct($file, $xml)
    {
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $this->file = $file;
        $this->xml = simplexml_load_string($xml);
        $errors = libxml_get_errors();
        if (count($errors)) {
            foreach ($errors as &$error) {
                $error = (string) $error->message;
            }

            throw new Exception("$file is not formatted correctly: ".implode(', ', $errors));
        }
        $this->xml->addAttribute('filename', $file);
        $this->convert();
    }

    protected static function getXsltCollection()
    {
        if (is_null(self::$xslt_collection)) {
            if (class_exists('PrestaShopCollection')) {
                self::$xslt_collection = new PrestaShopCollection('Xslt');
            } else {
                self::$xslt_collection = new Collection('Xslt');
            }
        }
        return self::$xslt_collection;
    }

    protected function convert()
    {
        foreach (self::getXsltCollection() as $xslt) {
            if ((bool) $this->xml->xpath($xslt->xpath_query)) {
                libxml_use_internal_errors(true);

                if ($xslt->use_tpl) {
                    try {
                        $smarty = Context::getContext()->smarty;
                        $smarty->assign('root', $this->xml);
                        $result = $smarty->fetch('string:'.$xslt->xml);
                        $this->xml = new SimpleXMLElement($result);
                    } catch (Exception $e) {
                        throw new Exception(
                            "Result of the Xslt #{$xslt->id} of the file $this->file is not a valid XML: "
                            .$e->getMessage()
                        );
                    }
                    $errors = libxml_get_errors();
                    if (count($errors)) {
                        foreach ($errors as &$error) {
                            $error = (string) $error->message;
                        }

                        throw new Exception(
                            "Result of the Xslt #{$xslt->id} of the file $this->file is not a valid XML: "
                            .implode(', ', $errors)
                        );
                    }
                } else {
                    $xslt_document = new SimpleXMLElement($xslt->xml);
                    $errors = libxml_get_errors();
                    if (count($errors)) {
                        foreach ($errors as &$error) {
                            $error = (string) $error->message;
                        }

                        throw new Exception(
                            "Xslt #{$xslt->id} is not formatted correctly: "
                            .implode(', ', $errors)
                        );
                    }
                    $proc = new XSLTProcessor();
                    $proc->importStylesheet($xslt_document);
                    libxml_use_internal_errors(true);
                    $xml_transformed = $proc->transformToXML($this->xml);
                    if ($xml_transformed === false) {
                        throw new Exception("Xslt #{$xslt->id} do not match with file $this->file");
                    }

                    $this->xml = new SimpleXMLElement($xml_transformed);
                    $errors = libxml_get_errors();
                    if (count($errors)) {
                        foreach ($errors as &$error) {
                            $error = (string) $error->message;
                        }

                        throw new Exception(
                            "Result of the Xslt #{$xslt->id} of the file $this->file is not a valid XML: "
                            .implode(', ', $errors)
                        );
                    }
                }
            }
        }
    }

    public function getXml()
    {
        return $this->xml;
    }
}
