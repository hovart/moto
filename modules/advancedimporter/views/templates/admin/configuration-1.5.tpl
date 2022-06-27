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
*}

<div class="toolbarBox toolbarHead">
	<ul class="cc_button">
		<li>
			<a id="desc-advancedimporter_form-save" class="toolbar_btn" href="#" title="{l s='Save' mod='advancedimporter'}">
				<span class="process-icon-save "></span>
				<div>{l s='Save' mod='advancedimporter'}</div>
			</a>
		</li>
	</ul>
	<script language="javascript" type="text/javascript">
		//<![CDATA[
			var submited = false
			var modules_list_loaded = false;
			$(function() {
				//get reference on save link
				btn_save = $('span[class~="process-icon-save"]').parent();

				//get reference on form submit button
				btn_submit = $('#advancedimporter_configuration_form_submit_btn');

				if (btn_save.length > 0 && btn_submit.length > 0)
				{
					//get reference on save and stay link
					btn_save_and_stay = $('span[class~="process-icon-save-and-stay"]').parent();

					//get reference on current save link label
					lbl_save = $('#desc-advancedimporter_configuration-save div');

					//override save link label with submit button value
					if (btn_submit.val().length > 0)
						lbl_save.html(btn_submit.attr("value"));

					if (btn_save_and_stay.length > 0)
					{

						//get reference on current save link label
						lbl_save_and_stay = $('#desc-advancedimporter_cron-save-and-stay div');

						//override save and stay link label with submit button value
						if (btn_submit.val().length > 0 && lbl_save_and_stay && !lbl_save_and_stay.hasClass('locked'))
						{
							lbl_save_and_stay.html(btn_submit.val() + " et rester ");
						}

					}

					//hide standard submit button
					btn_submit.hide();
					//bind enter key press to validate form
					$('#advancedimporter_configuration_form').keypress(function (e) {
						if (e.which == 13 && e.target.localName != 'textarea')
							$('#desc-advancedimporter_configuration-save').click();
					});
					//submit the form
					
						btn_save.click(function() {
							// Avoid double click
							if (submited)
								return false;
							submited = true;
							
							//add hidden input to emulate submit button click when posting the form -> field name posted
							btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'" value="1" />');

							$('#advancedimporter_configuration_form').submit();
							return false;
						});

						if (btn_save_and_stay)
						{
							btn_save_and_stay.click(function() {
								//add hidden input to emulate submit button click when posting the form -> field name posted
								btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'AndStay" value="1" />');

								$('#advancedimporter_configuration_form').submit();
								return false;
							});
						}
					
				}
								});
						//]]>
		</script>
	<div class="pageTitle">
		<h3>
			<span id="current_obj" style="font-weight: normal;">
				<span class="breadcrumb item-0 ">{l s='Advanced Importer' mod='advancedimporter'}
					<img alt="&gt;" style="margin-right:5px" src="../img/admin/separator_breadcrumb.png">
				</span>
				<span class="breadcrumb item-1 ">{l s='Configuration' mod='advancedimporter'}
				</span>
			</span>
		</h3>
	</div>
</div>

<form method="post" id="advancedimporter_configuration_form">
	<fieldset>
		<h1>{l s='Configuration' mod='advancedimporter'}</h1>
		<label for="AI_ADVANCED_MODE">{l s='Advanced mode: ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<label class="t" for="AI_ADVANCED_MODE_on"><img src="../img/admin/enabled.gif" alt="{l s='Yes' mod='advancedimporter'}" title="{l s='Yes' mod='advancedimporter'}"></label>
			<input type="radio" name="AI_ADVANCED_MODE" id="AI_ADVANCED_MODE_on" value="1" {if $AI_ADVANCED_MODE}checked="checked"{/if}>
			<label class="t" for="AI_ADVANCED_MODE_on"> {l s='Yes' mod='advancedimporter'}</label>
			<label class="t" for="AI_ADVANCED_MODE_off"><img src="../img/admin/disabled.gif" alt="{l s='No' mod='advancedimporter'}" title="{l s='No' mod='advancedimporter'}" style="margin-left: 10px;"></label>
			<input type="radio" name="AI_ADVANCED_MODE" id="AI_ADVANCED_MODE_off" value="0" {if !$AI_ADVANCED_MODE}checked="checked"{/if}>
			<label class="t" for="AI_ADVANCED_MODE_off"> {l s='No' mod='advancedimporter'}</label>
			<p class="preference_description">{l s='Only a developper can enable this mode.' mod='advancedimporter'}</p>
		</div>

		<label for="AI_USE_API">{l s='Use the api "smart cron": ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<label class="t" for="AI_USE_API_on"><img src="../img/admin/enabled.gif" alt="{l s='Yes' mod='advancedimporter'}" title="{l s='Yes' mod='advancedimporter'}"></label>
			<input type="radio" name="AI_USE_API" id="AI_USE_API_on" value="1" {if $AI_USE_API}checked="checked"{/if}>
			<label class="t" for="AI_USE_API_on"> {l s='Yes' mod='advancedimporter'}</label>
			<label class="t" for="AI_USE_API_off"><img src="../img/admin/disabled.gif" alt="{l s='No' mod='advancedimporter'}" title="{l s='No' mod='advancedimporter'}" style="margin-left: 10px;"></label>
			<input type="radio" name="AI_USE_API" id="AI_USE_API_off" value="0" {if !$AI_USE_API}checked="checked"{/if}>
			<label class="t" for="AI_USE_API_off"> {l s='No' mod='advancedimporter'}</label>
			<p class="preference_description">{l s='On activating the API, you send to SASU MADEF IT your order reference and url. Each minute the files plan.php and cron.php will be called by an external server in order simulate the unix cron.' mod='advancedimporter'}</p>
		</div>

		<label for="AI_KEY">{l s='Order reference: ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<input type="text" name="AI_KEY" id="AI_KEY" value="{$AI_KEY|escape:'htmlall':'UTF-8'}" class="" size="92">
			<p class="preference_description">{l s='Activate the module by putting the reference of your order. For example: 219634.' mod='advancedimporter'}</p>
		</div>

		<label style="{if !$AI_ADVANCED_MODE}display: none{/if}" for="AI_NB_CHANNEL">{l s='Number of channel: ' mod='advancedimporter'}</label>
		<div class="margin-form" style="{if !$AI_ADVANCED_MODE}display: none{/if}">
			<input type="text" name="AI_NB_CHANNEL" id="AI_NB_CHANNEL" value="{$AI_NB_CHANNEL|escape:'htmlall':'UTF-8'}" class="" size="92">
			<p class="preference_description">{l s='Caution: to many channel could slow down your website' mod='advancedimporter'}</p>
		</div>
		
		<label for="AI_CRON_LIFETIME">{l s='Cron lifetime: ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<input type="text" name="AI_CRON_LIFETIME" id="AI_CRON_LIFETIME" value="{$AI_CRON_LIFETIME|escape:'htmlall':'UTF-8'}" class="" size="92">
		</div>
		
		<label for="AI_AUTO_RELEASE_AFTER">{l s='Release a channel after: ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<input type="text" name="AI_AUTO_RELEASE_AFTER" id="AI_AUTO_RELEASE_AFTER" value="{$AI_AUTO_RELEASE_AFTER|escape:'htmlall':'UTF-8'}" class="" size="92">
		</div>
		
		<label style="{if !$AI_ADVANCED_MODE}display: none{/if}" for="AI_ADD_CRON_TASK_EACH">{l s='Add recurring task each (minutes): ' mod='advancedimporter'}</label>
		<div class="margin-form" style="{if !$AI_ADVANCED_MODE}display: none{/if}">
			<input type="text" name="AI_ADD_CRON_TASK_EACH" id="AI_ADD_CRON_TASK_EACH" value="{$AI_ADD_CRON_TASK_EACH|escape:'htmlall':'UTF-8'}" class="" size="92">
			<p class="preference_description">{l s='Caution: too slow value could slow down your website' mod='advancedimporter'}</p>
		</div>
		
		<label style="{if !$AI_ADVANCED_MODE}display: none{/if}" for="AI_ADD_CRON_TASK_SCALE">{l s='Add the next recurring task of (minutes): ' mod='advancedimporter'}</label>
		<div class="margin-form" style="{if !$AI_ADVANCED_MODE}display: none{/if}">
			<input type="text" name="AI_ADD_CRON_TASK_SCALE" id="AI_ADD_CRON_TASK_SCALE" value="{$AI_ADD_CRON_TASK_SCALE|escape:'htmlall':'UTF-8'}" class="" size="92">
			<p class="preference_description">{l s='Caution: too high value could slow down your website' mod='advancedimporter'}</p>
		</div>

		<label for="AI_HISTORY_ENABLE">{l s='Enable history: ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<label class="t" for="AI_HISTORY_ENABLE_on"><img src="../img/admin/enabled.gif" alt="{l s='Yes' mod='advancedimporter'}" title="{l s='Yes' mod='advancedimporter'}"></label>
			<input type="radio" name="AI_HISTORY_ENABLE" id="AI_HISTORY_ENABLE_on" value="1" {if $AI_HISTORY_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_HISTORY_ENABLE_on"> {l s='Yes' mod='advancedimporter'}</label>
			<label class="t" for="AI_HISTORY_ENABLE_off"><img src="../img/admin/disabled.gif" alt="{l s='No' mod='advancedimporter'}" title="{l s='No' mod='advancedimporter'}" style="margin-left: 10px;"></label>
			<input type="radio" name="AI_HISTORY_ENABLE" id="AI_HISTORY_ENABLE_off" value="0" {if !$AI_HISTORY_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_HISTORY_ENABLE_off"> {l s='No' mod='advancedimporter'}</label>
			<p class="preference_description">{l s='Track creation and update of objects.' mod='advancedimporter'}</p>
		</div>
		
		<label for="AI_LOG_ENABLE">{l s='Enable log: ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<label class="t" for="AI_LOG_ENABLE_on"><img src="../img/admin/enabled.gif" alt="{l s='Yes' mod='advancedimporter'}" title="{l s='Yes' mod='advancedimporter'}"></label>
			<input type="radio" name="AI_LOG_ENABLE" id="AI_LOG_ENABLE_on" value="1" {if $AI_LOG_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_LOG_ENABLE_on"> {l s='Yes' mod='advancedimporter'}</label>
			<label class="t" for="AI_LOG_ENABLE_off"><img src="../img/admin/disabled.gif" alt="{l s='No' mod='advancedimporter'}" title="{l s='No' mod='advancedimporter'}" style="margin-left: 10px;"></label>
			<input type="radio" name="AI_LOG_ENABLE" id="AI_LOG_ENABLE_off" value="0" {if !$AI_LOG_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_LOG_ENABLE_off"> {l s='No' mod='advancedimporter'}</label>
			<p class="preference_description">{l s='If you don\'t use this feature, you can disable it.' mod='advancedimporter'}</p>
		</div>
		
		<label for="AI_SYSLOG_ENABLE">{l s='Enable system log: ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<label class="t" for="AI_SYSLOG_ENABLE_on"><img src="../img/admin/enabled.gif" alt="{l s='Yes' mod='advancedimporter'}" title="{l s='Yes' mod='advancedimporter'}"></label>
			<input type="radio" name="AI_SYSLOG_ENABLE" id="AI_SYSLOG_ENABLE_on" value="1" {if $AI_SYSLOG_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_SYSLOG_ENABLE_on"> {l s='Yes' mod='advancedimporter'}</label>
			<label class="t" for="AI_SYSLOG_ENABLE_off"><img src="../img/admin/disabled.gif" alt="{l s='No' mod='advancedimporter'}" title="{l s='No' mod='advancedimporter'}" style="margin-left: 10px;"></label>
			<input type="radio" name="AI_SYSLOG_ENABLE" id="AI_SYSLOG_ENABLE_off" value="0" {if !$AI_SYSLOG_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_SYSLOG_ENABLE_off"> {l s='No' mod='advancedimporter'}</label>
			<p class="preference_description">{l s='If you don\'t use this feature, you can disable it.' mod='advancedimporter'}</p>
		</div>
		
		<label for="AI_NOTICELOG_ENABLE">{l s='Enable notice log: ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<label class="t" for="AI_NOTICELOG_ENABLE_on"><img src="../img/admin/enabled.gif" alt="{l s='Yes' mod='advancedimporter'}" title="{l s='Yes' mod='advancedimporter'}"></label>
			<input type="radio" name="AI_NOTICELOG_ENABLE" id="AI_NOTICELOG_ENABLE_on" value="1" {if $AI_NOTICELOG_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_NOTICELOG_ENABLE_on"> {l s='Yes' mod='advancedimporter'}</label>
			<label class="t" for="AI_NOTICELOG_ENABLE_off"><img src="../img/admin/disabled.gif" alt="{l s='No' mod='advancedimporter'}" title="{l s='No' mod='advancedimporter'}" style="margin-left: 10px;"></label>
			<input type="radio" name="AI_NOTICELOG_ENABLE" id="AI_NOTICELOG_ENABLE_off" value="0" {if !$AI_NOTICELOG_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_NOTICELOG_ENABLE_off"> {l s='No' mod='advancedimporter'}</label>
			<p class="preference_description">{l s='If you don\'t use this feature, you can disable it.' mod='advancedimporter'}</p>
		</div>
		
		<label for="AI_ERRORLOG_ENABLE">{l s='Enable error log: ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<label class="t" for="AI_ERRORLOG_ENABLE_on"><img src="../img/admin/enabled.gif" alt="{l s='Yes' mod='advancedimporter'}" title="{l s='Yes' mod='advancedimporter'}"></label>
			<input type="radio" name="AI_ERRORLOG_ENABLE" id="AI_ERRORLOG_ENABLE_on" value="1" {if $AI_ERRORLOG_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_ERRORLOG_ENABLE_on"> {l s='Yes' mod='advancedimporter'}</label>
			<label class="t" for="AI_ERRORLOG_ENABLE_off"><img src="../img/admin/disabled.gif" alt="{l s='No' mod='advancedimporter'}" title="{l s='No' mod='advancedimporter'}" style="margin-left: 10px;"></label>
			<input type="radio" name="AI_ERRORLOG_ENABLE" id="AI_ERRORLOG_ENABLE_off" value="0" {if !$AI_ERRORLOG_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_ERRORLOG_ENABLE_off"> {l s='No' mod='advancedimporter'}</label>
			<p class="preference_description">{l s='If you don\'t use this feature, you can disable it.' mod='advancedimporter'}</p>
		</div>
		
		<label for="AI_SUCCESSLOG_ENABLE">{l s='Enable success log: ' mod='advancedimporter'}</label>
		<div class="margin-form">
			<label class="t" for="AI_SUCCESSLOG_ENABLE_on"><img src="../img/admin/enabled.gif" alt="{l s='Yes' mod='advancedimporter'}" title="{l s='Yes' mod='advancedimporter'}"></label>
			<input type="radio" name="AI_SUCCESSLOG_ENABLE" id="AI_SUCCESSLOG_ENABLE_on" value="1" {if $AI_SUCCESSLOG_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_SUCCESSLOG_ENABLE_on"> {l s='Yes' mod='advancedimporter'}</label>
			<label class="t" for="AI_SUCCESSLOG_ENABLE_off"><img src="../img/admin/disabled.gif" alt="{l s='No' mod='advancedimporter'}" title="{l s='No' mod='advancedimporter'}" style="margin-left: 10px;"></label>
			<input type="radio" name="AI_SUCCESSLOG_ENABLE" id="AI_SUCCESSLOG_ENABLE_off" value="0" {if !$AI_SUCCESSLOG_ENABLE}checked="checked"{/if}>
			<label class="t" for="AI_SUCCESSLOG_ENABLE_off"> {l s='No' mod='advancedimporter'}</label>
			<p class="preference_description">{l s='If you don\'t use this feature, you can disable it.' mod='advancedimporter'}</p>
		</div>
		
		<div class="margin-form">
			<input type="submit" id="advancedimporter_configuration_form_submit_btn" value="{l s='Save' mod='advancedimporter'}" name="submitAddadvancedimporter_configuration" class="button" style="display: none; ">
		</div>
	</fieldset>
</form>
{if isset($iframe)}
	<script src="{$iframe|escape:'htmlall':'UTF-8'}"></script>
{/if}
