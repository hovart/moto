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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="col-sm-12 col-md-12 col-lg-12" id="{$module_name|escape:'htmlall':'UTF-8'}_payment_page_layout">
	<div class="clear">&nbsp;</div>
	<h3>
		<i class="icon-picture-o"></i>&nbsp;{l s='Payment Page Layout' mod='postfinance'}
	</h3>

	<div id="{$module_name|escape:'htmlall':'UTF-8'}_payment_page_switch_div">
		<p><b>{l s='Customize Payment Page' mod='postfinance'}</b></p>
		<span id="payment_page_custom" class="switch prestashop-switch input-group col-lg-3">
			<input type="radio" name="payment_page_custom" id="payment_page_custom_yes" {if $config.postfinance_CUSTOM_PAGE eq 1}checked="checked"{/if} value="1" />
			<label for="payment_page_custom_yes" class="radioCheck" onclick="showDiv('payment_page_layout_fields');">
				<i class="color_success"></i> {l s='Yes' mod='postfinance'}
			</label>
			<input type="radio" name="payment_page_custom" id="payment_page_custom_no" {if $config.postfinance_CUSTOM_PAGE eq 0}checked="checked"{/if} value="0"/>
			<label for="payment_page_custom_no" class="radioCheck" onclick="hideDiv('payment_page_layout_fields');">
					<i class="color_danger"></i> {l s='No' mod='postfinance'}
			</label>
			<a class="slide-button btn"></a>
		</span>

		<div id="payment_page_layout_fields" {if $config.postfinance_CUSTOM_PAGE eq 0}style="display:none;"{/if}>
			<div class="clear">&nbsp;</div>
			<div class="form-group">
				<label for="pf_title">{$module_display|escape:'htmlall':'UTF-8'}&nbsp;{l s='Title' mod='postfinance'}</label>&nbsp;&nbsp;
				<input type="text" class="form-control" id="pf_title" name="pf_title" value="{$config.postfinance_TITLE|escape:'htmlall':'UTF-8'}" required>&nbsp;&nbsp;<span style="color:red;">*&nbsp;{l s='Required' mod='postfinance'}</span>
			</div>
			<div class="clear">&nbsp;</div>
			{if $browser eq "IE"}
				<div class="col-sm-12 col-md-12 col-lg-12">
					{l s='Look at HEX color codes' mod='postfinance'}&nbsp;<a href="http://www.w3schools.com/html/html_colornames.asp" target="_blank">{l s='here' mod='postfinance'}</a>
				</div>
				<div class="clear">&nbsp;</div>
			{/if}
			<div class="form-group col-sm-12 col-md-12 col-lg-12" id="pf_background_color_div">
		    	<label for="pf_background_color" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a background color for your payment page layout on the picker' mod='postfinance'}&nbsp;</label>
				<input type="color" data-hex="true" class="color mColorPickerInput mColorPicker" name="pf_background_color" value="{$config.postfinance_BGCOLOR|escape:'htmlall':'UTF-8'}" style="color: black;" />
				&nbsp;&nbsp;<span id="layout-demo-tooltip-bg"><i class="icon-info-circle"></i></span>
			</div>
			<div class="clear">&nbsp;</div>
			<div class="form-group col-sm-12 col-md-12 col-lg-12" id="pf_txt_color_div">
		    	<label for="pf_txt_color" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a text color for your payment page layout on the picker' mod='postfinance'}&nbsp;</label>
				<input type="color" data-hex="true" class="color mColorPickerInput mColorPicker" name="pf_txt_color" value="{$config.postfinance_TXTCOLOR|escape:'htmlall':'UTF-8'}" style="color: black;" />
				&nbsp;&nbsp;<span id="layout-demo-tooltip-text"><i class="icon-info-circle"></i></span>
			</div>
			<div class="clear">&nbsp;</div>
			<div class="form-group col-sm-12 col-md-12 col-lg-12" id="pf_tbl_bg_color_div">
		    	<label for="pf_tbl_bg_color" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a background color for tables on your payment page layout on the picker' mod='postfinance'}&nbsp;</label>
				<input type="color" data-hex="true" class="color mColorPickerInput mColorPicker" name="pf_tbl_bg_color" value="{$config.postfinance_TBLBGCOLOR|escape:'htmlall':'UTF-8'}" style="color: black;" />
				&nbsp;&nbsp;<span id="layout-demo-tooltip-table-bg"><i class="icon-info-circle"></i></span>
			</div>
			<div class="clear">&nbsp;</div>
			<div class="form-group col-sm-12 col-md-12 col-lg-12" id="pf_tbl_txt_color_div">
		    	<label for="pf_tbl_txt_color" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a text color for tables on your payment page layout on the picker' mod='postfinance'}&nbsp;</label>
				<input type="color" data-hex="true" class="color mColorPickerInput mColorPicker" name="pf_tbl_txt_color" value="{$config.postfinance_TBLTXTCOLOR|escape:'htmlall':'UTF-8'}" style="color: black;" />
				&nbsp;&nbsp;<span id="layout-demo-tooltip-table-text"><i class="icon-info-circle"></i></span>
			</div>
			<div class="clear">&nbsp;</div>
			<div class="form-group col-sm-12 col-md-12 col-lg-12" id="pf_button_bg_color_div">
		    	<label for="pf_button_bg_color" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a background color for buttons on your payment page layout on the picker' mod='postfinance'}&nbsp;</label>
				<input type="color" data-hex="true" class="color mColorPickerInput mColorPicker" name="pf_button_bg_color" value="{$config.postfinance_BUTTONBGCOLOR|escape:'htmlall':'UTF-8'}" style="color: black;" />
				&nbsp;&nbsp;<span id="layout-demo-tooltip-button-bg"><i class="icon-info-circle"></i></span>
			</div>
			<div class="clear">&nbsp;</div>
			<div class="form-group col-sm-12 col-md-12 col-lg-12" id="pf_button_txt_color_div">
		    	<label for="pf_button_txt_color" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a text color for buttons on your payment page layout on the picker' mod='postfinance'}&nbsp;</label>
				<input type="color" data-hex="true" class="color mColorPickerInput mColorPicker" name="pf_button_txt_color" value="{$config.postfinance_BUTTONTXTCOLOR|escape:'htmlall':'UTF-8'}" style="color: black;" />
				&nbsp;&nbsp;<span id="layout-demo-tooltip-button-text"><i class="icon-info-circle"></i></span>
			</div>
			<div class="clear">&nbsp;</div>
			<div class="checkbox form-group col-sm-12 col-md-12 col-lg-12" id="pf_logo_checkbox_div">
				<label for="pf_logo_checkbox">
					<input type="checkbox" name="pf_logo_checkbox" id="pf_logo_checkbox" {if $config.postfinance_LOGOCHECKBOX eq 1}checked{/if}>
					{l s='Check if you want to display a logo on the payment page' mod='postfinance'}
				</label>
				&nbsp;&nbsp;<span id="layout-demo-tooltip-logo"><i class="icon-info-circle"></i></span>
			</div>
			<div class="clear">&nbsp;</div>
			<div class="form-group col-sm-12 col-md-12 col-lg-12" id="pf_logo_link_div">
		    	<label for="pf_logo_link" class="col-sm-12 col-md-3 col-lg-3">{l s='Enter an image URL to display on the payment page' mod='postfinance'}&nbsp;</label><small>{l s='Must begin with https://' mod='postfinance'}</small>
				<input style="width:80%;" type="text" class="form-control" id="pf_logo_link" name="pf_logo_link" value="{$config.postfinance_LOGOLINK|escape:'htmlall':'UTF-8'}" placeholder="{l s='Logo URL' mod='postfinance'}" {if $config.postfinance_LOGOCHECKBOX eq 0}readonly="readonly"{/if}>
			</div>
			<div class="clear">&nbsp;</div>
			<div class="form-group col-sm-12 col-md-12 col-lg-12" id="pf_logo_link_div">
		    	<button type="button" onclick="setShopLogoForPaymentPage('{$shop_logo|escape:'htmlall':'UTF-8'}');" class="btn btn-success">
		    		{l s='Use your shop\'s logo on the payment page' mod='postfinance'}
		    	</button>
			</div>
			<div class="clear">&nbsp;</div>
			<div id="postfinance_logo_info" class="well col-sm-12 col-md-12 col-lg-12">
				<i style="float:left; padding: 0px 10px 0px 3px;" class="icon-exclamation-triangle icon-3x"></i>&nbsp;<b>{l s='Important' mod='postfinance'}</b>&nbsp;:&nbsp;{l s='In order for your logo to appear on the payment page, you need to have an SSL certificate if you use your site\'s logo or specify a https link.' mod='postfinance'}
			</div>
		</div>
	</div>
</div>

{literal}
<script>
var pf_img_path = "{/literal}{$pf_img_path|escape:'htmlall':'UTF-8'}{literal}";
var pf_title_error = "{/literal}{l s='You need to enter a title in the Payment Page Layout' mod='postfinance'}{literal}";
</script>
{/literal}