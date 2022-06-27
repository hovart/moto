{*
* 2013-2016 MADEF IT
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    MADEF IT <contact@madef.fr>
*  @copyright 2013-2016 SASU MADEF IT
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../modules/advancedimporter/views/css/handsontable.full.css">
        <style>
            #save {
                position: absolute;
                background: white;
                bottom: 2px;
                left: 5%;
                width: 90%;
                line-height: 85px;
                height: 85px;
                border: 1px solid black;
                text-align: center;
                border-radius: 3px;
                color: #363A41;
                border-color: #DEDEDE;
                font-size: 17px;
                cursor: pointer;
            }
            #save:hover {
                color: #fff;
                background-color: #00aff0;
                border-color: #008abd;
            }
        </style>
        <script src="../modules/advancedimporter/views/js/handsontable.full.js"></script>
        <script src="{$jquery|escape:'htmlall':'UTF-8'}"></script>
    </head>
    <body>
        <div style="overflow: hidden; position: absolute; top: 0; bottom: 100px; left: 0; right: 0; min-height: 200px" id="content"></div>
        <button id="save">{l s='Save the template' mod='advancedimporter'}</button>
        <script>
            var table;

            window.setTimeout(function() {
                table = new Handsontable(
                    document.getElementById('content'),
                    {$content|json_encode}
                );

                table.updateSettings({
                    cells: function (row, col, prop) {
                        var cellProperties = {};

                        if (row != 0) {
                            return {};
                        }

                        return {
                            editor: 'select',
                            selectOptions: {$fields|json_encode},
                            readOnly: false
                        }
                    }
                });
            }, 500);

            document.getElementById('save').onclick = function() {
                var node = "";
                for (i in table.getDataAtRow(0)) {
                    node += "&node[" + i + "]=" + table.getDataAtRow(0)[i];
                }

                window.location.href = window.location.href.replace('preview', 'createCsvTemplate') + node;
            };
        </script>
    </body>
</html>
