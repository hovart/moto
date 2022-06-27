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

function upgrade_module_1_12($module)
{
    // Process Module upgrade to 1.12

    $return = true;
    $return &= Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advancedimporter_xslt` (
            `id_advancedimporter_xslt` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `xml` TEXT,
            `xpath_query` VARCHAR(255) DEFAULT NULL,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_advancedimporter_xslt`)
        ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
    ');
    /*
    $return &= Db::getInstance()->execute('
        INSERT INTO `'._DB_PREFIX_.'advancedimporter_xslt` SET
            `xml` = \'<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet  [
    <!ENTITY nbsp   " ">
    <!ENTITY copy   "©">
    <!ENTITY reg    "®">
    <!ENTITY trade  "™">
    <!ENTITY mdash  "—">
    <!ENTITY ldquo  "“">
    <!ENTITY rdquo  "”">
    <!ENTITY pound  "£">
    <!ENTITY yen    "¥">
    <!ENTITY euro   "€">
]>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"
        doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"/>
    <xsl:template match="/"><![CDATA[
        <xsl:comment> This is an example of XSLT. By creating XSLT, you can use your own syntax of XML </xsl:comment>
        <xsl:comment> Here start the template </xsl:comment>
        <objects>
            <xsl:for-each select="product">
                <object type="product">
                    <xsl:if test="sku">
                        <xsl:attribute name="external-refrence">
                            <xsl:value-of select="sku" />
                        </xsl:attribute>
                    </xsl:if>
                    <name lang="{/@language}"><xsl:value-of select="name" /></name>
                    <link_rewrite lang="{/@language}"><xsl:value-of select="name" /></link_rewrite>
                    <price><xsl:value-of select="price_tax_exclude" /></price>
                    <id_tax_rule>1</id_tax_rule>
                    <xsl:if test="category">
                        <external_reference for="id_category_default" type="Category"><xsl:value-of select="category" /></external_reference>
                    </xsl:if>
                    <active>1</active>
                </object>
            </xsl:for-each>
        </objects>
        <xsl:comment> Here end the template </xsl:comment>
    ]]></xsl:template>
</xsl:stylesheet>\',
            `xpath_query` = "/products/@language",
            `date_add` = NOW(),
            `date_upd` = NOW()
    ');
    $return &= Db::getInstance()->execute('
        INSERT INTO `'._DB_PREFIX_.'advancedimporter_xslt` SET
            `xml` = \'<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet  [
    <!ENTITY nbsp   " ">
    <!ENTITY copy   "©">
    <!ENTITY reg    "®">
    <!ENTITY trade  "™">
    <!ENTITY mdash  "—">
    <!ENTITY ldquo  "“">
    <!ENTITY rdquo  "”">
    <!ENTITY pound  "£">
    <!ENTITY yen    "¥">
    <!ENTITY euro   "€">
]>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" encoding="utf-8" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"
        doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"/>
    <xsl:template match="/">
        <xsl:comment> This is an example of XSLT. By creating XSLT, you can use your own syntax of XML </xsl:comment>
        <xsl:comment> Here start the template </xsl:comment>
        <objects>
            <xsl:for-each select="category">
                <object type="category">
                    <xsl:if test="id">
                        <xsl:attribute name="external-refrence">
                            <xsl:value-of select="id" />
                        </xsl:attribute>
                    </xsl:if>
                    <name lang="{/@language}"><xsl:value-of select="name" /></name>
                    <link_rewrite lang="{/@language}"><xsl:value-of select="name" /></link_rewrite>
                    <xsl:if test="parent">
                        <external_reference for="id_parent" type="Category"><xsl:value-of select="parent" /></external_reference>
                    </xsl:if>
                    <active>1</active>
                </object>
            </xsl:for-each>
        </objects>
        <xsl:comment> Here end the template </xsl:comment>
    </xsl:template>
</xsl:stylesheet>\',
            `xpath_query` = "/categories/@language",
            `date_add` = NOW(),
            `date_upd` = NOW()
    ');

     */

    $return &= Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'tab
        WHERE module = "advancedimporter"');

    $return &= Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'module_access
        WHERE `id_module` = '.(int) $module->id);

    $module->createAdminTabs();

    return $return;
}
