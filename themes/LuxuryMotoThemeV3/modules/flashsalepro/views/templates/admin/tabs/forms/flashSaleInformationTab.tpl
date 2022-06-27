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

<div id="flash_sale_info_div">
    <div class="clear">&nbsp;</div>
    <div class="col-sm-12 col-md-12 col-lg-12" id="flash_type_div" style="padding-left: 0px;">
        <label>{l s='Flash Sale Type' mod='flashsalepro'}</label>&nbsp;&nbsp;
        <div class="btn-group" data-toggle="buttons">
            <label style="width:200px;" for="flash_type_time" class="btn btn-info" id="flash_type_time_label" onclick="update_div_class('flash_type_time_label');">
                    <input type="radio" name="flash_type" id="flash_type_time" value="timed" checked='checked'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{l s='Timed Sale (Classic)' mod='flashsalepro'}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </label>
            <label style="width:200px;" for="flash_type_stock" class="btn btn-default" id="flash_type_stock_label" onclick="update_div_class('flash_type_stock_label');">
                    <input type="radio" name="flash_type" id="flash_type_stock" value="stock">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{l s='Stock Dependent' mod='flashsalepro'}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </label>
            <label style="width:200px;" for="flash_type_manual" class="btn btn-default" id="flash_type_manual_label" onclick="update_div_class('flash_type_manual_label');">
                    <input type="radio" name="flash_type" id="flash_type_manual" value="manual" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{l s='Manual' mod='flashsalepro'}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </label>
        </div>
    </div>

    <div class="clear">&nbsp;</div>
    <div id="flash_sale_dates_div" style="padding-left: 0px;">
        <div class="form_group" id='flash_sale_date_from_div'>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style="padding-left: 0px;">
                <label>{l s='Start of Sale' mod='flashsalepro'}</label>&nbsp;&nbsp;
            </div>
            <div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>
                <input type='text' class="" id='flash_sale_date_from' name="flash_sale_date_from" />&nbsp;&nbsp;<span style="color:red;">*</span>
            </div>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#flash_sale_date_from').datetimepicker({ dateFormat: 'yy-mm-dd', pickDate: true, pickTime: true});
            });
        </script>

        <div class="form_group" id='flash_sale_date_to_div'>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style="padding-left: 0px;">
                <label>{l s='End of Sale' mod='flashsalepro'}</label>&nbsp;&nbsp;
            </div>
            <div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>
                <input type='text' class="" id='flash_sale_date_to' name="flash_sale_date_to" />&nbsp;&nbsp;<span style="color:red;">*</span>
            </div>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#flash_sale_date_to').datetimepicker({ dateFormat: 'yy-mm-dd', pickDate: true, pickTime: true});
            });
        </script>
    </div>

    <div class="clear">&nbsp;</div>
    <label>{l s='Would you like the sale to be restricted to a specific customer group :' mod='flashsalepro'}</label>&nbsp;&nbsp;
    <div id="discount_group_restriction_div">
        <select class="col-xs-12 col-sm-12 col-md-5 col-lg-3" id="discount_group_restriction" name="discount_group_restriction">
            <option value="0" />{l s='No Restriction' mod='flashsalepro'}</option>
            {foreach from=$groups key=k item=v}
                {if $v.name neq "FlashSalePro Disabled"}<option value="{$v.id_group|escape:'htmlall':'UTF-8'}" />{$v.name|escape:'htmlall':'UTF-8'}</option>{/if}
            {/foreach}
        </select>
    </div>
    <div class="clear">&nbsp;</div>
    <div class="clear">&nbsp;</div>
    <label>{l s='Would you like the sale to be restricted to a specific currency :' mod='flashsalepro'}</label>&nbsp;&nbsp;
    <div id="discount_currency_restriction_div">
        <select class="col-xs-12 col-sm-12 col-md-5 col-lg-3" id="discount_currency_restriction" name="discount_currency_restriction">
            <option value="0" />{l s='No Restriction' mod='flashsalepro'}</option>
            {foreach from=$currencies key=k item=v}
                <option value="{$v.id_currency|escape:'htmlall':'UTF-8'}" />{$v.name|escape:'htmlall':'UTF-8'}</option>
            {/foreach}
        </select>
    </div>
    <div class="clear">&nbsp;</div>
    <div class="clear">&nbsp;</div>
    <label>{l s='Would you like the sale to be restricted to a specific country :' mod='flashsalepro'}</label>&nbsp;&nbsp;
    <div id="discount_country_restriction_div">
        <select class="col-xs-12 col-sm-12 col-md-5 col-lg-3" id="discount_country_restriction" name="discount_country_restriction">
            <option value="0" />{l s='No Restriction' mod='flashsalepro'}</option>
            {foreach from=$countries key=k item=v}
                <option value="{$v.id_country|escape:'htmlall':'UTF-8'}" />{$v.name|escape:'htmlall':'UTF-8'}</option>
            {/foreach}
        </select>
    </div>
    <div class="clear">&nbsp;</div>
    <button style="float:right;" class="btn btn-default" type="button" onClick="step2('forward');"><i class="icon-arrow-circle-right"></i>&nbsp;&nbsp;{l s="Next" mod='flashsalepro'}</button>
    <div class="clear">&nbsp;</div>
</div>
