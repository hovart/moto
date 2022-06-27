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

<div class="modal fade modal-padding" id="edit_flash_sale_modal" tabindex="-1" role="dialog" aria-labelledby="contract_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">                                      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="contract_modal_title">{l s='Edit Flash Sale' mod='flashsalepro'}</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12 col-md-12 col-lg-12" onclick="showEditDivSections('show_edit_name', 'edit_flash_sale_name_div');" id="show_edit_name" style="cursor:pointer;">
                    <h3>{l s='Name' mod='flashsalepro'}&nbsp;&nbsp;<i class="icon-eye icon-2x"></i></h3>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12" id="edit_flash_sale_name_div" style="display:none;">
                    <div class="col-sm-12 col-md-12 col-lg-12" onclick="hideEditDivSections('edit_flash_sale_name_div', 'show_edit_name');" id="hide_edit_name" style="cursor:pointer;">
                        <h3>{l s='Name' mod='flashsalepro'}&nbsp;&nbsp;<i class="icon-eye-close icon-2x"></i></h3>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        {foreach from=$languages key=k item=v}
                            <div class="translatable-field col-lg-8 lang-{$v.id_lang|escape:'htmlall':'UTF-8'}" id="edit_flash_sale_name_div_{$v.id_lang|escape:'htmlall':'UTF-8'}" name="edit_flash_sale_name_div_{$v.id_lang|escape:'htmlall':'UTF-8'}" style="display:none;">
                                <input type="text" class="form-control edit-flash-sale-name" renos-face="{$v.id_lang|escape:'htmlall':'UTF-8'}" id="edit_flash_sale_name_{$v.id_lang|escape:'htmlall':'UTF-8'}" name="edit_flash_sale_name_{$v.id_lang|escape:'htmlall':'UTF-8'}">
                            </div>
                        {/foreach}

                        <div class="dropdown col-lg-3">
                            <button class="btn btn-default dropdown-toggle" type="button" id="edit_flash_sale_name_lang" data-toggle="dropdown" aria-expanded="true">
                                {$languages.0.iso_code|escape:'htmlall':'UTF-8'}&nbsp;<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuFlashSaleNameLang">
                                {foreach from=$languages key=k item=v}
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#" onclick="hideFlashSaleLangs({$v.id_lang|escape:'htmlall':'UTF-8'}, '{$v.iso_code|escape:'htmlall':'UTF-8'}');">{$v.name|escape:'htmlall':'UTF-8'}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <p class="help-block">{l s='If you want to change the name of the Flash Sale, please check all languages available.' mod='flashsalepro'}</p>
                        </div>
                    </div>
                    <div class="clear">&nbsp;</div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-12" onclick="showEditDivSections('show_edit_restrictions', 'edit_flash_sales_restrictions');" id="show_edit_restrictions" style="cursor:pointer;">
                    <h3>{l s='Restrictions' mod='flashsalepro'}&nbsp;&nbsp;<i class="icon-eye icon-2x"></i></h3>
                </div>
                <div id="edit_flash_sales_restrictions" style="display:none;" class="col-sm-12 col-md-12 col-lg-12">
                    <div class="col-sm-12 col-md-12 col-lg-12" onclick="hideEditDivSections('edit_flash_sales_restrictions', 'show_edit_restrictions');" id="hide_edit_restrictions" style="cursor:pointer;">
                        <h3>{l s='Restrictions' mod='flashsalepro'}&nbsp;&nbsp;<i class="icon-eye-close icon-2x"></i></h3>
                    </div>
                    <label>{l s='Would you like the sale to be restricted to a specific customer group :' mod='flashsalepro'}</label>&nbsp;&nbsp;
                    <div id="edit_discount_group_restriction_div" class="col-sm-12 col-md-12 col-lg-12">
                        <select class="col-xs-12 col-sm-12 col-md-5 col-lg-3" id="edit_discount_group_restriction" name="edit_discount_group_restriction">
                            <option value="0" />{l s='No Restriction' mod='flashsalepro'}</option>
                            {foreach from=$groups key=k item=v}
                                {if $v.name neq "FlashSalePro Disabled"}<option value="{$v.id_group|escape:'htmlall':'UTF-8'}" />{$v.name|escape:'htmlall':'UTF-8'}</option>{/if}
                            {/foreach}
                        </select>
                    </div>
                    <div class="clear">&nbsp;</div>
                    <label>{l s='Would you like the sale to be restricted to a specific currency :' mod='flashsalepro'}</label>&nbsp;&nbsp;
                    <div id="edit_discount_currency_restriction_div" class="col-sm-12 col-md-12 col-lg-12">
                        <select class="col-xs-12 col-sm-12 col-md-5 col-lg-3" id="edit_discount_currency_restriction" name="edit_discount_currency_restriction">
                            <option value="0" />{l s='No Restriction' mod='flashsalepro'}</option>
                            {foreach from=$currencies key=k item=v}
                                <option value="{$v.id_currency|escape:'htmlall':'UTF-8'}" />{$v.name|escape:'htmlall':'UTF-8'}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="clear">&nbsp;</div>
                    <label>{l s='Would you like the sale to be restricted to a specific country :' mod='flashsalepro'}</label>&nbsp;&nbsp;
                    <div id="edit_discount_country_restriction_div" class="col-sm-12 col-md-12 col-lg-12">
                        <select class="col-xs-12 col-sm-12 col-md-5 col-lg-3" id="edit_discount_country_restriction" name="edit_discount_country_restriction">
                            <option value="0" />{l s='No Restriction' mod='flashsalepro'}</option>
                            {foreach from=$countries key=k item=v}
                                <option value="{$v.id_country|escape:'htmlall':'UTF-8'}" />{$v.name|escape:'htmlall':'UTF-8'}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="clear">&nbsp;</div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-12" onclick="showEditDivSections('show_edit_colors', 'edit_flash_sales_colors');" id="show_edit_colors" style="cursor:pointer;">
                    <h3>{l s='Font and Colors' mod='flashsalepro'}&nbsp;&nbsp;<i class="icon-eye icon-2x"></i></h3>
                </div>
                <div id="edit_flash_sales_colors" style="display:none;" class="col-sm-12 col-md-12 col-lg-12">
                    <div class="col-sm-12 col-md-12 col-lg-12" onclick="hideEditDivSections('edit_flash_sales_colors', 'show_edit_colors');" id="hide_edit_colors" style="cursor:pointer;">
                        <h3>{l s='Font and Colors' mod='flashsalepro'}&nbsp;&nbsp;<i class="icon-eye-close icon-2x"></i></h3>
                    </div>

                     <div class="form-group col-sm-12 col-md-12 col-lg-12" id="edit_flashsale_bg_color_div">
                        <label for="edit_flashsale_bg_color" class="col-sm-12 col-md-6 col-lg-6">{l s='Choose a color for the background of your Flash Sale banner' mod='flashsalepro'}&nbsp;</label>
                        <input type="color" data-hex="true" class="color mColorPickerInput mColorPicker" name="edit_flashsale_bg_color" id="edit_flashsale_bg_color" />
                    </div>

                    <div class="form-group col-sm-12 col-md-12 col-lg-12" id="edit_flashsale_text_color_div">
                        <label for="edit_flashsale_text_color" class="col-sm-12 col-md-6 col-lg-6">{l s='Choose a color for the text on your Flash Sale banner' mod='flashsalepro'}&nbsp;</label>
                        <input type="color" data-hex="true" class="color mColorPickerInput mColorPicker" name="edit_flashsale_text_color" id="edit_flashsale_text_color" />
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-12" id="edit_flashsale_text_font_div">
                        <label for="edit_flashsale_text_font" class="col-sm-12 col-md-6 col-lg-6">{l s='Choose a font for the text on your Flash Sale banner' mod='flashsalepro'}&nbsp;</label>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <select name="edit_flashsale_text_font" id="edit_flashsale_text_font">
                                <option value="Arial">Arial</option>
                                <option value="'Arial Black'">Arial Black</option>
                                <option value="Helvetica">Helvetica</option>
                                <option value="Lucida">Lucida</option>
                                <option value="Times">Times</option>
                                <option value="Tahoma">Tahoma</option>
                                <option value="Verdana">Verdana</option>
                                <option value="Impact">Impact</option>
                                <option value="Georgia">Georgia</option>
                            </select>
                        </div>
                    </div>
                    <div class="clear">&nbsp;</div>
                </div>
                    
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <button type="button" style="float:right;" class="btn btn-default" data-dismiss="modal" onclick="updateFlashSaleInfo();"><i class="process-icon-save"></i>{l s='Save' mod='flashsalepro'}</button>
                </div>
                <div class="clear">&nbsp;</div>
                <!-- Product blocks for editing -->
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <h3>{l s='Products currently in the Flash Sale' mod='flashsalepro'}</h3>
                </div>

                <div id="edit_sale_view_product_div" class="col-sm-12 col-md-12 col-lg-12">
                    <div class="input-group">
                        <div class="input-group-addon"><i class="icon-search"></i></div>
                        <input style="width:50%;" placeholder="{l s='Search for a product in the Flash Sale.' mod='flashsalepro'}" autocomplete="off" class="form-control col-xs-12 col-sm-12 col-md-12 col-lg-12" type="text" id="edit_sale_view_product_search" name="edit_sale_view_product_search">
                    </div>
                </div>
                <div class="clear">&nbsp;</div>

                <div id="flash_sale_edit_product_block" class="col-sm-12 col-md-12 col-lg-12">
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12" id="pagnation_product_pages">
                    
                </div>
                <div class="clear">&nbsp;</div>
                <!-- Add products to sale -->
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <h3>{l s='Add products to Flash Sale' mod='flashsalepro'}</h3>
                </div>
                <div id="edit_sale_add_product_div" class="col-sm-12 col-md-12 col-lg-12">
                    <div class="input-group" style="float:right;">
                        <div class="input-group-addon"><i class="icon-search"></i></div>
                        <input style="width:50%;" placeholder="{l s='Search for a the product by name.' mod='flashsalepro'}" autocomplete="off" class="form-control col-xs-12 col-sm-12 col-md-12 col-lg-12" type="text" id="edit_sale_add_product_search" name="edit_sale_add_product_search">
                    </div>
                    <div id="edit_sale_add_product_search_results_confirmation" class="col-sm-12 col-md-12 col-lg-12">
                    </div>
                    <div id="edit_sale_add_product_search_results" class="col-sm-12 col-md-12 col-lg-12 edit-sale-add-product-search-results">
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="modal-footer"> 
                <input type="hidden" id="id_flash_sale_to_edit" name="id_flash_sale_to_edit" value="">
                <input type="hidden" id="id_flash_sale_items_count" name="id_flash_sale_items_count" value="">
            </div> 
        </div>
    </div>
</div>

{literal}
<script>
$("#edit_flash_sale_name_div_{/literal}{$id_lang_default|escape:'htmlall':'UTF-8'}{literal}").show();
var item_pagination_current_index = 1;
</script>
{/literal}