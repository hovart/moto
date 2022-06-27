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

    <div class="form-group" id="flash_sale_name_div">
        <label for="flash_sale_name_1">{l s='Flash Sale Name' mod='flashsalepro'}</label>
        {foreach from=$languages key=k item=v}
                <div class="translatable-field col-lg-9 lang-{$v.id_lang|escape:'htmlall':'UTF-8'}" id="flash_sale_name_div_{$v.id_lang|escape:'htmlall':'UTF-8'}" name="flash_sale_name_div_{$v.id_lang|escape:'htmlall':'UTF-8'}" style="display:none;">
                    <input type="text" class="form-control" id="flash_sale_name_{$v.id_lang|escape:'htmlall':'UTF-8'}" name="flash_sale_name_{$v.id_lang|escape:'htmlall':'UTF-8'}" placeholder="{l s='Enter name here' mod='flashsalepro'}">&nbsp;&nbsp;<span style="color:red;">*</span>
                </div>
        {/foreach}

        <div class="dropdown col-lg-2">
            <button class="btn btn-default dropdown-toggle" type="button" id="flash_sale_name_lang" data-toggle="dropdown" aria-expanded="true">
                {$languages.0.iso_code|escape:'htmlall':'UTF-8'}&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuFlashSaleNameLang">
                {foreach from=$languages key=k item=v}
                    <li role="presentation" id="li_{$v.id_lang|escape:'htmlall':'UTF-8'}"><a role="menuitem" tabindex="-1" href="#" onclick="hideFlashSaleLangs({$v.id_lang|escape:'intval'}, '{$v.iso_code|escape:'htmlall':'UTF-8'}');">{$v.name|escape:'htmlall':'UTF-8'}</a></li>
                {/foreach}
            </ul>
        </div>
    </div>
    <div class="clear">&nbsp;</div>

    <div id="flash_sale_image">
        <label for="custom_flashsale_img">{l s='Choose a custom image for flash sale banner' mod='flashsalepro'}&nbsp;</label>
        <small style="color:red;">&nbsp;({l s='Optional' mod='flashsalepro'})</small>
        <input type="file" name="custom_flashsale_img" id="custom_flashsale_img">
        
        <small>{l s='Recommended size: 500px X 160px' mod='flashsalepro'}</small>
        <p>{l s='If no custom image is chosen, the default image will be used.' mod='flashsalepro'}</p>
        <span style="cursor:pointer;" onclick="showFlashSaleDefaultImage();" id="show_default_img">
            <i class="icon-eye icon-2x"></i>
            <small>&nbsp;&nbsp;{l s='View default image' mod='flashsalepro'}</small>
        </span>
        <div id="flash_sale_default_img_div" style="display:none;">
            <span style="cursor:pointer;" onclick="hideFlashSaleDefaultImage();" id="hide_default_img">
                <i class="icon-eye-slash icon-2x"></i>
                <small>&nbsp;&nbsp;{l s='Hide default image' mod='flashsalepro'}</small>
            </span>
            <img src="{$image_default|escape:'htmlall':'UTF-8'}" alt="Default Image" >
        </div>
    </div>

    <div class="clear">&nbsp;</div>

    <div class="form-group col-sm-12 col-md-12 col-lg-12" id="flashsale_bg_color_div">
        <label for="flashsale_bg_color" class="col-sm-12 col-md-6 col-lg-6">{l s='Choose a color for the background of your Flash Sale banner' mod='flashsalepro'}&nbsp;</label>
        <input type="color" data-hex="true" class="color mColorPickerInput mColorPicker" name="flashsale_bg_color" value="#ffffff" />
    </div>
    <div class="clear">&nbsp;</div>
    <div class="form-group col-sm-12 col-md-12 col-lg-12" id="flashsale_text_color_div">
        <label for="flashsale_text_color" class="col-sm-12 col-md-6 col-lg-6">{l s='Choose a color for the text on your Flash Sale banner' mod='flashsalepro'}&nbsp;</label>
        <input type="color" data-hex="true" class="color mColorPickerInput mColorPicker" name="flashsale_text_color" />
    </div>

    <div class="clear">&nbsp;</div>
    <div class="form-group col-sm-12 col-md-12 col-lg-12" id="flashsale_text_font_div">
        <label for="flashsale_text_font" class="col-sm-12 col-md-6 col-lg-6">{l s='Choose a font for the text on your Flash Sale banner' mod='flashsalepro'}&nbsp;</label>
        <select name="flashsale_text_font" id="flashsale_text_font">
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
    <div class="clear">&nbsp;</div>
    <button style="float:left;" class="btn btn-default" type="button" onClick="step1();" id="back_step2"><i class="icon-arrow-circle-left"></i>&nbsp;&nbsp;{l s="Back" mod='flashsalepro'}</button>
    <button style="float:right;" class="btn btn-default" type="button" onClick="step3();"><i class="icon-arrow-circle-right"></i>&nbsp;&nbsp;{l s="Next" mod='flashsalepro'}</button>
    <div class="clear">&nbsp;</div>
</div>
{literal}
<script>
$("#flash_sale_name_div_{/literal}{$id_lang_default|escape:'htmlall':'UTF-8'}{literal}").show();

$('.translatable-field').hide();
$(".lang-{/literal}{$id_lang_default|escape:'htmlall':'UTF-8'}{literal}").show();
$("#flash_sale_name_lang").html('{/literal}{$default_language_iso_code|escape:"htmlall":"UTF-8"}{literal}&nbsp;<span class="caret"></span>');
$("#edit_flash_sale_name_lang").html('{/literal}{$default_language_iso_code|escape:"htmlall":"UTF-8"}{literal}&nbsp;<span class="caret"></span>');
</script>
{/literal}