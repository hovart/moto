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
<div id="flash_sale_pro_creation">
	<h3>
		<i class="icon-link"></i> {l s='Flash Sales Pro Creation' mod='flashsalepro'} <small>{$module_display|escape:'htmlall':'UTF-8'}</small>
	</h3>
	{$alert_sale_creation} <!-- already escaped in function called -->
	<div> <!-- Create new flash sale button -->
			<center>
				<button type="button" style="margin-left:auto;margin-right:auto;width:17%;" class="btn btn-default" data-toggle="modal" data-target="#new_flash_sale_modal"><i class="process-icon-new"></i>&nbsp;&nbsp;{l s='Create New Flash Sale' mod='flashsalepro'}</button>
			</center>
	</div>
	<div id="flash_sales_pro_info_and_table" {if $flash_sale_count eq 0}style="display:none;"{/if}>
		<div class="clear">&nbsp;</div>
		<div id="update_date_start">
		</div>

		<div id="update_date_end">
		</div>
		<div class="clear">&nbsp;</div>
		<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-hover">
			<tr>
				<th>{l s='View' mod='flashsalepro'}</th>
				<th>{l s='ID' mod='flashsalepro'}</th>
				<th>{l s='Name' mod='flashsalepro'}</th>
				<th>{l s='Start Date' mod='flashsalepro'}</th>
				<th>{l s='Expiration Date' mod='flashsalepro'}</th>
				<th>{l s='Active' mod='flashsalepro'}</th>
				<th>{l s='Flash Sale Type' mod='flashsalepro'}</th>
				<th>{l s='Groups' mod='flashsalepro'}</th>
				<th>{l s='Countries' mod='flashsalepro'}</th>
				<th>{l s='Currency' mod='flashsalepro'}</th>
				<th>{l s='Delete' mod='flashsalepro'}</th>
			</tr>
			<tbody id="flash_sale_info_table_body">
				{include file="./flashsaleTable.tpl"}
			</tbody>
		</table>
		<div class="col-sm-12 col-md-12 col-lg-12">
	        <p class="help-block">{l s='Reminder : Timed Flash Sales that have a future expiration date will be activated automatically if no other Flash Sales are active.' mod='flashsalepro'}</p>
	    </div>
	</div>

	<div class="clear">&nbsp;</div>
	<div id="flash_sale_item_table_div" style="display:none;">
		<h3>{l s='Flash Sales Details' mod='flashsalepro'}</h3>
		<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-hover" id="flash_sale_item_table">
			<tr id="item_table_stock_header_row">

			</tr>
			<tbody id="flash_sale_item_table_body">

			</tbody>
		</table>
	</div>
	<div class="clear">&nbsp;</div>
	<div id="flash_sale_pro_configuration">
		<h3>
			<i class="icon-link"></i> {l s='Flash Sales Pro Configuration' mod='flashsalepro'} <small>{$module_display|escape:'htmlall':'UTF-8'}</small>
		</h3>
		<form role="form" class="form-inline" action="{$requestUri|escape:'htmlall':'UTF-8'}" method="post">
		<div class="col-sm-12 col-md-12 col-lg-12 tighten-up">
			<div class="clear">&nbsp;</div>
		    <div class="col-sm-12 col-md-12 col-lg-12">
				<label for="flash_tax_switch" class="col-sm-12 col-md-3 col-lg-3">
					{l s='Discount includes tax :' mod='flashsalepro'}
					<div class="clear">&nbsp;</div>
				</label>
		        <span id="flash_tax_switch" class="switch prestashop-switch input-group col-sm-12 col-md-3 col-lg-3" style="display:inline-block;">
		            <input type="radio" name="flash_tax_switch" id="flash_tax_switch_yes" value="1" {if $flash_tax_switch eq 1}checked="checked"{/if} />
		            <label for="flash_tax_switch_yes" class="radioCheck">
		                    <i class="color_success"></i> {l s='Yes' mod='flashsalepro'}
		            </label>
		            <input type="radio" name="flash_tax_switch" id="flash_tax_switch_no" value="0" {if $flash_tax_switch eq 0}checked="checked"{/if} />
		            <label for="flash_tax_switch_no" class="radioCheck">
		                <i class="color_success"></i> {l s='No' mod='flashsalepro'}
		            </label>
		            <a class="slide-button btn"></a>
		        </span>
		    </div>

			<div class="clear">&nbsp;</div>
		    <div class="col-sm-12 col-md-12 col-lg-12">
		        <p class="help-block">{l s='Choose only one column from the right/left options' mod='flashsalepro'}</p>
		    </div>
		    <div class="col-sm-12 col-md-12 col-lg-12">
				<label for="flash_left_column_switch" class="col-sm-12 col-md-3 col-lg-3">
					{l s='Show active Flash Sale on left column :' mod='flashsalepro'}
					<div class="clear">&nbsp;</div>
				</label>
		        <span id="flash_left_column_switch" class="switch prestashop-switch input-group col-sm-12 col-md-3 col-lg-3" style="display:inline-block;">
		            <input type="radio" name="flash_left_column_switch" id="flash_left_column_switch_yes" value="1" {if $flash_left_column_switch eq 1}checked="checked"{/if} />
		            <label for="flash_left_column_switch_yes" class="radioCheck" onclick="changeColumn('left');">
		                    <i class="color_success"></i> {l s='Yes' mod='flashsalepro'}
		            </label>
		            <input type="radio" name="flash_left_column_switch" id="flash_left_column_switch_no" value="0" {if $flash_left_column_switch eq 0}checked="checked"{/if} />
		            <label for="flash_left_column_switch_no" class="radioCheck">
		                <i class="color_success"></i> {l s='No' mod='flashsalepro'}
		            </label>
		            <a class="slide-button btn"></a>
		        </span>
		        <p class="help-block">{l s='This will not appear on mobile devices or any screens with width resolution of less than 992px' mod='flashsalepro'}</p>
		    </div>

		    <div class="clear">&nbsp;</div>
		    <div class="col-sm-12 col-md-12 col-lg-12">
				<label for="flash_right_column_switch" class="col-sm-12 col-md-3 col-lg-3">
					{l s='Show active Flash Sale on right column :' mod='flashsalepro'}
					<div class="clear">&nbsp;</div>
				</label>
		        <span id="flash_right_column_switch" class="switch prestashop-switch input-group col-sm-12 col-md-3 col-lg-3" style="display:inline-block;">
		            <input type="radio" name="flash_right_column_switch" id="flash_right_column_switch_yes" value="1" {if $flash_right_column_switch eq 1}checked="checked"{/if} />
		            <label for="flash_right_column_switch_yes" class="radioCheck" onclick="changeColumn('right');">
		                    <i class="color_success"></i> {l s='Yes' mod='flashsalepro'}
		            </label>
		            <input type="radio" name="flash_right_column_switch" id="flash_right_column_switch_no" value="0" {if $flash_right_column_switch eq 0}checked="checked"{/if} />
		            <label for="flash_right_column_switch_no" class="radioCheck">
		                <i class="color_success"></i> {l s='No' mod='flashsalepro'}
		            </label>
		            <a class="slide-button btn"></a>
		        </span>
		        <p class="help-block">{l s='This will not appear on mobile devices or any screens with width resolution of less than 992px' mod='flashsalepro'}</p>
		    </div>

		    <div class="clear">&nbsp;</div>
		    <div class="col-sm-12 col-md-12 col-lg-12">
				<label for="flash_product_page_right_switch" class="col-sm-12 col-md-3 col-lg-3">
					{l s='Show active Flash Sale on all product pages :' mod='flashsalepro'}
					<div class="clear">&nbsp;</div>
				</label>
		        <span id="flash_product_page_right_switch" class="switch prestashop-switch input-group col-sm-12 col-md-3 col-lg-3" style="display:inline-block;">
		            <input type="radio" name="flash_product_page_right_switch" id="flash_product_page_right_switch_yes" value="1" {if $flash_product_page_right_switch eq 1}checked="checked"{/if} />
		            <label for="flash_product_page_right_switch_yes" class="radioCheck">
		                    <i class="color_success"></i> {l s='Yes' mod='flashsalepro'}
		            </label>
		            <input type="radio" name="flash_product_page_right_switch" id="flash_product_page_right_switch_no" value="0" {if $flash_product_page_right_switch eq 0}checked="checked"{/if} />
		            <label for="flash_product_page_right_switch_no" class="radioCheck">
		                <i class="color_success"></i> {l s='No' mod='flashsalepro'}
		            </label>
		            <a class="slide-button btn"></a>
		        </span>
		        <p class="help-block">{l s='This will take preference over left/right column' mod='flashsalepro'}</p>
		    </div>

		    <div class="clear">&nbsp;</div>
		    <div class="col-sm-12 col-md-12 col-lg-12">
				<label for="flash_category_left_column_switch" class="col-sm-12 col-md-3 col-lg-3">
					{l s='Show active Flash Sale on all category pages :' mod='flashsalepro'}
					<div class="clear">&nbsp;</div>
				</label>
		        <span id="flash_category_left_column_switch" class="switch prestashop-switch input-group col-sm-12 col-md-3 col-lg-3" style="display:inline-block;">
		            <input type="radio" name="flash_category_left_column_switch" id="flash_category_left_column_switch_yes" value="1" {if $flash_category_left_column_switch eq 1}checked="checked"{/if} />
		            <label for="flash_category_left_column_switch_yes" class="radioCheck">
		                    <i class="color_success"></i> {l s='Yes' mod='flashsalepro'}
		            </label>
		            <input type="radio" name="flash_category_left_column_switch" id="flash_category_left_column_switch_no" value="0" {if $flash_category_left_column_switch eq 0}checked="checked"{/if} />
		            <label for="flash_category_left_column_switch_no" class="radioCheck">
		                <i class="color_success"></i> {l s='No' mod='flashsalepro'}
		            </label>
		            <a class="slide-button btn"></a>
		        </span>
		        <p class="help-block">{l s='This will take preference over left/right column' mod='flashsalepro'}</p>
		    </div>

		    <div class="clear">&nbsp;</div>
		    <div class="col-sm-12 col-md-12 col-lg-12">
				<label for="corner_banner_switch" class="col-sm-12 col-md-3 col-lg-3">
					{l s='Include the corner banner on Flash Sale :' mod='flashsalepro'}
					<div class="clear">&nbsp;</div>
				</label>
		        <span id="corner_banner_switch" class="switch prestashop-switch input-group col-sm-12 col-md-3 col-lg-3" style="display:inline-block;">
		            <input type="radio" name="corner_banner_switch" id="corner_banner_switch_yes" value="1" {if $corner_banner_switch eq 1}checked="checked"{/if} />
		            <label for="corner_banner_switch_yes" class="radioCheck" onclick="showCornerBannerConfig();">
		                    <i class="color_success"></i> {l s='Yes' mod='flashsalepro'}
		            </label>
		            <input type="radio" name="corner_banner_switch" id="corner_banner_switch_no" value="0" {if $corner_banner_switch eq 0}checked="checked"{/if} />
		            <label for="corner_banner_switch_no" class="radioCheck" onclick="hideCornerBannerConfig();">
		                <i class="color_success"></i> {l s='No' mod='flashsalepro'}
		            </label>
		            <a class="slide-button btn"></a>
		        </span>
				<p class="help-block">{l s='This corner banner will not appear on mobile devices or on Internet Explorer' mod='flashsalepro'}</p>
		    </div>
		    <div class="clear">&nbsp;</div>

		    <div id="corner_banner_config" {if $corner_banner_switch eq 0}style="display:none;"{/if}>
			    <div class="col-sm-12 col-md-9 col-lg-9">
				    <div class="col-sm-12 col-md-12 col-lg-12">
						<label for="corner_banner_text" class="col-sm-12 col-md-3 col-lg-3 tighten-up">{l s='Choose text for your Flash Sale corner banner :' mod='flashsalepro'}&nbsp;</label>
					    <div id="corner_banner_text" class="col-sm-12 col-md-4 col-lg-4">
					    	<input type="text" value="{$corner_banner_text|escape:'htmlall':'UTF-8'}" name="corner_banner_text" id="corner_banner_text" />
					    	<small>{l s='Suggested max : 14 characters' mod='flashsalepro'}</small>
				    	</div>
				    </div>
					<div class="clear">&nbsp;</div>
					<div class="form-group col-sm-12 col-md-12 col-lg-12" id="banner_font_div">
				        <label for="banner_font" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a font for the Flash Sale corner banner' mod='flashsalepro'}&nbsp;</label>
				        <select name="banner_font" id="banner_font">
				            <option value="Arial" {if $banner_font eq "Arial"}selected{/if}>Arial</option>
				            <option value="'Arial Black'" {if $banner_font eq "'Arial Black'"}selected{/if}>Arial Black</option>
				            <option value="Helvetica" {if $banner_font eq "Helvetica"}selected{/if}>Helvetica</option>
				            <option value="Lucida" {if $banner_font eq "Lucida"}selected{/if}>Lucida</option>
				            <option value="Times" {if $banner_font eq "Times"}selected{/if}>Times</option>
				            <option value="Tahoma" {if $banner_font eq "Tahoma"}selected{/if}>Tahoma</option>
				            <option value="Verdana" {if $banner_font eq "Verdana"}selected{/if}>Verdana</option>
				            <option value="Impact" {if $banner_font eq "Impact"}selected{/if}>Impact</option>
				            <option value="Georgia" {if $banner_font eq "Georgia"}selected{/if}>Georgia</option>
				        </select>
				    </div>
					<div class="clear">&nbsp;</div>
				    <div class="form-group col-sm-12 col-md-12 col-lg-12" id="banner-colors-text">
				    	<label for="banner_text_color" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a color for the text on your Flash Sale corner banner' mod='flashsalepro'}&nbsp;</label>
				    	<div class="col-lg-2">
							<div class="row">
								<div class="input-group">
									<input type="text" data-hex="true" class="color mColorPickerInput mColorPicker" name="banner_text_color" id="banner_text_color" value="{$banner_text_color|escape:'htmlall':'UTF-8'}" style="color: black; background-color:{$banner_text_color|escape:'htmlall':'UTF-8'};" onchange="setTimerColor('text_banner');" />
									<span style="cursor:pointer;" id="icp_banner_text_color" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="../img/admin/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>
								</div>
							</div>
						</div>
						<div class="clear">&nbsp;</div>
					</div>

					<div class="clear">&nbsp;</div>
				    <div class="form-group col-sm-12 col-md-12 col-lg-12" id="banner-colors-background">
				    	<label for="banner_bg_color" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a background color for your Flash Sale corner banner' mod='flashsalepro'}&nbsp;</label>
				    	<div class="col-lg-2">
							<div class="row">
								<div class="input-group">
									<input type="text" data-hex="true" class="color mColorPickerInput mColorPicker" name="banner_bg_color" id="banner_bg_color" value="{$banner_bg_color|escape:'htmlall':'UTF-8'}" style="color:black; background-color:{$banner_bg_color|escape:'htmlall':'UTF-8'};" onchange="setTimerColor('bg_banner');"/>
									<span style="cursor:pointer;" id="icp_banner_bg_color" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="../img/admin/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>
								</div>
							</div>
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>

				<div id="corner_banner_preview" class="col-sm-12 col-md-3 col-lg-3 well">
					<div class="clear">&nbsp;</div>
					<h3><u>{l s='Banner Preview' mod='flashsalepro'}</u></h3>
					<span style="color:{$banner_text_color|escape:'htmlall':'UTF-8'};background-color:{$banner_bg_color|escape:'htmlall':'UTF-8'};" class="col-sm-12 col-md-12 col-lg-12" id="banner_preview">
						<div>
							<center>
								<h2 style="font-family:{$banner_font|escape:'htmlall':'UTF-8'};"><b>{$corner_banner_text|escape:'htmlall':'UTF-8'}</b></h2>
							</center>
						</div>
					</span>
					<div class="clear">&nbsp;</div>
				</div>
				<div class="clear">&nbsp;</div>
		    </div>
		</div>
	</div>

	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="clear">&nbsp;</div>
		<h3>
			<i class="icon-time"></i> {l s='Flash Sale Timer Design' mod='flashsalepro'} <small>{$module_display|escape:'htmlall':'UTF-8'}</small>
		</h3>
		<div class="form-group col-sm-12 col-md-12 col-lg-12" id="timer-color-background">
			<div class="clear">&nbsp;</div>
			
			<div class="row col-sm-12 col-md-12 col-lg-12">
				<label for="timer_bg_color" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a background color for your Flash Sale timer' mod='flashsalepro'}&nbsp;</label>
				<div class="col-lg-2">
					<div class="row">
						<div class="input-group">
							<input type="text" data-hex="true" class="color mColorPickerInput mColorPicker" name="timer_bg_color" id="timer_bg_color" value="{$timer_bg_color|escape:'htmlall':'UTF-8'}" style="color: black; background-color:{$timer_bg_color|escape:'htmlall':'UTF-8'};" onchange="setTimerColor('bg');" />
							<span style="cursor:pointer;" id="icp_timer_bg_color" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="../img/admin/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>
						</div>
					</div>
				</div>
				<div class="clear">&nbsp;</div>
			</div>

			<div class="row col-sm-12 col-md-12 col-lg-12">
				<label for="timer_text_color" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a text color for your Flash Sale timer' mod='flashsalepro'}&nbsp;</label>
				<div class="col-lg-2">
					<div class="row">
						<div class="input-group">
							<input type="text" data-hex="true" class="color mColorPickerInput mColorPicker" name="timer_text_color" id="timer_text_color" value="{$timer_text_color|escape:'htmlall':'UTF-8'}" style="color: black; background-color:{$timer_text_color|escape:'htmlall':'UTF-8'};"  onchange="setTimerColor('text');"/>
							<span style="cursor:pointer;" id="icp_timer_text_color" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="../img/admin/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>
						</div>
					</div>
				</div>
				<div class="clear">&nbsp;</div>
			</div>

			<div class="row col-sm-12 col-md-12 col-lg-12">
				<label for="timer_dot_color" class="col-sm-12 col-md-3 col-lg-3">{l s='Choose a dot color for your Flash Sale timer' mod='flashsalepro'}&nbsp;</label>
				<div class="col-lg-2">
					<div class="row">
						<div class="input-group">
							<input type="text" data-hex="true" class="color mColorPickerInput mColorPicker" name="timer_dot_color" id="timer_dot_color" value="{$timer_dot_color|escape:'htmlall':'UTF-8'}" style="color: black; background-color:{$timer_dot_color|escape:'htmlall':'UTF-8'};"  onchange="setTimerColor('dot');"/>
							<span style="cursor:pointer;" id="icp_timer_dot_color" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="../img/admin/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>
						</div>
					</div>
				</div>
				<div class="clear">&nbsp;</div>
			</div>
		</div>

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tighten-up">
			<div class="clear">&nbsp;</div>
			<div class="clock"></div>
		</div>
	</div>
	<div class="clear">&nbsp;</div>
	<!-- Switch to add basic bootstrap grid css to front office -->
	 <div class="col-sm-12 col-md-12 col-lg-12">
				<label for="flash_add_grid_css" class="col-sm-12 col-md-3 col-lg-3">
					{l s='Include Bootstrap Grid CSS file' mod='flashsalepro'}
					<div class="clear">&nbsp;</div>
				</label>
		        <span id="flash_add_grid_css" class="switch prestashop-switch input-group col-sm-12 col-md-3 col-lg-3" style="display:inline-block;">
		            <input type="radio" name="flash_add_grid_css" id="flash_add_grid_css_yes" value="1" {if $flash_add_grid_css eq 1}checked="checked"{/if} />
		            <label for="flash_add_grid_css_yes" class="radioCheck">
		                    <i class="color_success"></i> {l s='Yes' mod='flashsalepro'}
		            </label>
		            <input type="radio" name="flash_add_grid_css" id="flash_add_grid_css_no" value="0" {if $flash_add_grid_css eq 0}checked="checked"{/if} />
		            <label for="flash_add_grid_css_no" class="radioCheck">
		                <i class="color_success"></i> {l s='No' mod='flashsalepro'}
		            </label>
		            <a class="slide-button btn"></a>
		        </span>
		        <p class="help-block">{l s='Only choose yes if there is a problem with how the banner is displayed on the site\'s front office' mod='flashsalepro'}</p>
		    </div>

	<div class="col-sm-12 col-md-12 col-lg-12">
		<center>
			<button type="submit" name="submitFlashSaleConfig" class="btn btn-default"><i class='process-icon-save'></i>{l s='Update settings' mod='flashsalepro'}</button>
		</center>
		</form>
	</div>
	<div class="clear">&nbsp;</div>
</div>

{include file="./forms/newFlashSale.tpl"}
{include file="./forms/editFlashSale.tpl"}


{literal}
<script>
/* Variables used in flipclock.min.js for inline style on element of class "inn" */
var timer_bg_color = "{/literal}{$timer_bg_color|escape:'htmlall':'UTF-8'}{literal}";
var timer_text_color = "{/literal}{$timer_text_color|escape:'htmlall':'UTF-8'}{literal}";
var timer_dot_color = "{/literal}{$timer_dot_color|escape:'htmlall':'UTF-8'}{literal}";

var future_timestamp = "{/literal}{$future_timestamp|escape:'htmlall':'UTF-8'}{literal}";
var lang_code = "{/literal}{$default_language_iso_code|escape:'htmlall':'UTF-8'}{literal}";
var lang = "{/literal}{$lang|escape:'htmlall':'UTF-8'}{literal}";
var id_lang_default = "{/literal}{$id_lang_default|escape:'htmlall':'UTF-8'}{literal}";
var default_currency_sign = "{/literal}{$default_currency_sign|escape:'htmlall':'UTF-8'}{literal}";
var sale_created_alert = "{/literal}{$sale_created_alert|escape:'htmlall':'UTF-8'}{literal}";
var sale_created_header = "{/literal}{l s='Flash Sale Created' mod='flashsalepro' js=1}{literal}";
var sale_created_text = "{/literal}{l s='Don\'t forget to activate your sale in the table below.' mod='flashsalepro' js=1}{literal}";
var flash_existing_sp = "{/literal}{l s='This product has an existing specific price' mod='flashsalepro' js=1}{literal}";

</script>
{/literal}