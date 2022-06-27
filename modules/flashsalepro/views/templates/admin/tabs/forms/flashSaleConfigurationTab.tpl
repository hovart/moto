{*
* 2007-2015 PrestaShop
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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}
<div>
    <div class="clear">&nbsp;</div>
    <label>{l s='Add items to the flash sale.' mod='flashsalepro'}</label>&nbsp;&nbsp;
    <div class="clear">&nbsp;</div>
    <div class="btn-group" data-toggle="buttons">
        <label style="width:200px;" for="search_type_product" class="btn btn-info" id="search_type_product_label" onclick="update_div_class('search_type_product_label');">
                <input type="radio" name="search_type" id="search_type_product" value="0" checked='checked'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{l s='Product' mod='flashsalepro'}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </label>
        <label style="width:200px;" for="search_type_category" class="btn btn-default" id="search_type_category_label" onclick="update_div_class('search_type_category_label');">
                <input type="radio" name="search_type" id="search_type_category" value="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{l s='Category' mod='flashsalepro'}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </label>
    </div>
    <input type="hidden" name="trans_search" id="trans_search" value="">
    <div class="clear">&nbsp;</div>

    <div id="flash_sale_category_search_info" style="display:none;">
        <p class="well"><i class="icon-info-sign"></i>&nbsp;&nbsp;{l s='If you add a category, all the products in the category will be added excluding products with an active specific price that has been created outside the module.' mod='flashsalepro'}<br />{l s='If you add a product and a category that contains the added product, the configuration for the individual product will be given priority in the flash sale.' mod='flashsalepro'}
            <br />{l s='Parent categories always get priority. If you add 2 categories, the configuration for the parent category will be taken.' mod='flashsalepro'}</p>
    </div>

    <div class="row" id="product-search-div">
        <div class="well col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <input style="width: 100%;" placeholder="{l s='Enter the product/category name.' mod='flashsalepro'}" autocomplete="off" class="form-control col-xs-12 col-sm-12 col-md-12 col-lg-12" type="text" id="liveSearch" name="liveSearch">
            <div class="clear">&nbsp;</div>
            <div id="liveSearchResultsCategories" class="live-search-results-categories">
                &nbsp;
            </div>
            <div id="liveSearchResults" class="live-search-results">
                &nbsp;
            </div>
        </div>
        <div class="well col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <span id="selectedProduct">
                <table id="selected_product_table" class="table table-striped col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <thead>
                        <tr id="selected_product_table_th">
                            <th>{l s='ID' mod='flashsalepro'}</th>
                            <th></th>
                            <th>{l s='Name' mod='flashsalepro'}</th>
                            <th>{l s='Discount' mod='flashsalepro'}</th>
                            <th>{l s='Action' mod='flashsalepro'}</th>
                        </tr>
                    </thead>
                    <tbody id="flashsales_selected_items_table_body">
                    </tbody>
                </table>
            </span>
        </div>
    </div>
    <div class="clear">&nbsp;</div>
    <button style="float:left;" class="btn btn-default" type="button" onClick="step2('back');" id="back_step2"><i class="icon-arrow-circle-left"></i>&nbsp;&nbsp;{l s="Back" mod='flashsalepro'}</button>
    <button style="float:right;" class="btn btn-default" type="button" onClick="step4();"><i class="icon-arrow-circle-right"></i>&nbsp;&nbsp;{l s="Next" mod='flashsalepro'}</button>
    <div class="clear">&nbsp;</div>
</div>