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

class EnrichConverterProductProduct
{
    public function __construct(&$nodes, $module)
    {
        $nodes['price_type'] = array(
            'name' => 'price_type',
            'schema' => array(
                'attributes' => array(),
                'children' => array(),
                'value' => true,
                'identifier' => 'price_type',
                'description' => $module->l('Prices include tax (ti ou te):'),
                'uniq' => true,
            ),
            'description' => $module->l('Price tax calculation'),
        );
        $nodes['tax'] = array(
            'name' => 'tax',
            'schema' => array(
                'attributes' => array(),
                'children' => array(),
                'value' => true,
                'identifier' => 'tax',
                'description' => $module->l('Tax rule name:'),
                'uniq' => true,
            ),
            'description' => $module->l('Tax rule name'),
        );
        $nodes['categorypath'] = array(
            'name' => 'categorypath',
            'schema' => array(
                'attributes' => array(
                    'separator',
                ),
                'children' => array(),
                'value' => true,
                'identifier' => 'categorypath',
                'description' => $module->l('Value:'),
                'uniq' => true,
            ),
            'description' => $module->l('Path of categories'),
        );
        $nodes['features'] = array(
            'name' => 'features',
            'schema' => array(
                'attributes' => array(
                    'external-reference',
                    'external-reference-value',
                    'id',
                    'id-value',
                    'name',
                    'name-value',
                    'custom',
                ),
                'children' => array(),
                'value' => true,
                'identifier' => 'features',
                'description' => $module->l('Value:'),
                'uniq' => false,
            ),
            'description' => $module->l('Features'),
        );
        $nodes['images'] = array(
            'name' => 'images',
            'schema' => array(
                'attributes' => array(),
                'children' => array(
                    'url' => array(
                        'uniq' => false,
                        'value' => true,
                        'identifier' => 'images/url',
                        'description' => $module->l('Image url:'),
                    ),
                ),
                'value' => false,
                'uniq' => true,
            ),
            'description' => $module->l('Image url'),
        );
        $nodes['stock'] = array(
            'name' => 'block',
            'schema' => array(
                'attributes' => array(),
                'children' => array(
                    'stocks' => array(
                        'uniq' => true,
                        'value' => false,
                        'attributes' => array(),
                        'children' => array(
                            'stock' => array(
                                'uniq' => true,
                                'value' => false,
                                'attributes' => array(),
                                'children' => array(
                                    'product' => array(
                                        'uniq' => true,
                                        'value' => false,
                                        'default-value' => '{{id}}',
                                        'attributes' => array(),
                                        'children' => array(),
                                    ),
                                    'mode' => array(
                                        'uniq' => true,
                                        'value' => true,
                                        'default-value' => 'set',
                                        'attributes' => array(),
                                        'children' => array(),
                                        'identifier' => 'stock/mode',
                                        'description' => $module->l('Mode (set, delta):'),
                                    ),
                                    'quantity' => array(
                                        'uniq' => true,
                                        'value' => true,
                                        'attributes' => array(),
                                        'children' => array(),
                                        'identifier' => 'stock/quantity',
                                        'description' => $module->l('Quantity:'),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'value' => false,
                'uniq' => false,
            ),
            'description' => $module->l('Stock'),
        );
    }
}
