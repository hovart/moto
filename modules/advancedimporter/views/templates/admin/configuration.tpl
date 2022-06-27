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

<form method="post" id="advancedimporter_configuration_form" class="defaultForm  form-horizontal">
	<fieldset class="panel">
		<h3>{l s='Configuration' mod='advancedimporter'}</h3>
		<div class="form-group">
			<label class="control-label col-lg-3" for="AI_ADVANCED_MODE">{l s='Advanced mode: ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<div class="row">
					<div class="input-group col-lg-2">
						<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="AI_ADVANCED_MODE" id="AI_ADVANCED_MODE_on" value="1" {if $AI_ADVANCED_MODE}checked="checked"{/if}>
						<label class="radioCheck" for="AI_ADVANCED_MODE_on">{l s='Yes' mod='advancedimporter'}</label>
						<input type="radio" name="AI_ADVANCED_MODE" id="AI_ADVANCED_MODE_off" value="0" {if !$AI_ADVANCED_MODE}checked="checked"{/if}>
						<label class="radioCheck" for="AI_ADVANCED_MODE_off">{l s='No' mod='advancedimporter'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
				<p class="help-block">{l s='Only a developper can enable this mode.' mod='advancedimporter'}</p>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-lg-3" for="AI_USE_API">{l s='Use the api "smart cron": ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<div class="row">
					<div class="input-group col-lg-2">
						<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="AI_USE_API" id="AI_USE_API_on" value="1" {if $AI_USE_API}checked="checked"{/if}>
						<label class="radioCheck" for="AI_USE_API_on">{l s='Yes' mod='advancedimporter'}</label>
						<input type="radio" name="AI_USE_API" id="AI_USE_API_off" value="0" {if !$AI_USE_API}checked="checked"{/if}>
						<label class="radioCheck" for="AI_USE_API_off">{l s='No' mod='advancedimporter'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
				<p class="help-block">{l s='On activating the API, you send to SASU MADEF IT your order reference and url. Each minute the files plan.php and cron.php will be called by an external server in order simulate the unix cron.' mod='advancedimporter'}</p>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3" for="AI_KEY">{l s='Order reference: ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<input type="text" name="AI_KEY" id="AI_KEY" value="{$AI_KEY|escape:'htmlall':'UTF-8'}" class="" size="92">
				<p class="help-block">{l s='Activate the module by putting the reference of your order. For example: 219634.' mod='advancedimporter'}</p>
			</div>
		</div>

		<div class="form-group {if !$AI_ADVANCED_MODE}hidden{/if}">
			<label class="control-label col-lg-3" for="AI_NB_CHANNEL">{l s='Number of channel: ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<input type="text" name="AI_NB_CHANNEL" id="AI_NB_CHANNEL" value="{$AI_NB_CHANNEL|escape:'htmlall':'UTF-8'}" class="" size="92">
				<p class="help-block">{l s='Caution: to many channel could slow down your website' mod='advancedimporter'}</p>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3" for="AI_CRON_LIFETIME">{l s='Cron lifetime: ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<input type="text" name="AI_CRON_LIFETIME" id="AI_CRON_LIFETIME" value="{$AI_CRON_LIFETIME|escape:'htmlall':'UTF-8'}" class="" size="92">
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3" for="AI_AUTO_RELEASE_AFTER">{l s='Release a channel after: ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<input type="text" name="AI_AUTO_RELEASE_AFTER" id="AI_AUTO_RELEASE_AFTER" value="{$AI_AUTO_RELEASE_AFTER|escape:'htmlall':'UTF-8'}" class="" size="92">
			</div>
		</div>
		
		<div class="form-group {if !$AI_ADVANCED_MODE}hidden{/if}">
			<label class="control-label col-lg-3" for="AI_ADD_CRON_TASK_EACH">{l s='Add recurring task each (minutes): ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<input type="text" name="AI_ADD_CRON_TASK_EACH" id="AI_ADD_CRON_TASK_EACH" value="{$AI_ADD_CRON_TASK_EACH|escape:'htmlall':'UTF-8'}" class="" size="92">
				<p class="help-block">{l s='Caution: too slow value could slow down your website' mod='advancedimporter'}</p>
			</div>
		</div>
		
		<div class="form-group {if !$AI_ADVANCED_MODE}hidden{/if}">
			<label class="control-label col-lg-3" for="AI_ADD_CRON_TASK_SCALE">{l s='Add the next recurring task of (minutes): ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<input type="text" name="AI_ADD_CRON_TASK_SCALE" id="AI_ADD_CRON_TASK_SCALE" value="{$AI_ADD_CRON_TASK_SCALE|escape:'htmlall':'UTF-8'}" class="" size="92">
				<p class="help-block">{l s='Caution: too high value could slow down your website' mod='advancedimporter'}</p>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-lg-3" for="AI_HISTORY_ENABLE">{l s='Enable history: ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<div class="row">
					<div class="input-group col-lg-2">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="AI_HISTORY_ENABLE" id="AI_HISTORY_ENABLE_on" value="1" {if $AI_HISTORY_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_HISTORY_ENABLE_on">{l s='Yes' mod='advancedimporter'}</label>
							<input type="radio" name="AI_HISTORY_ENABLE" id="AI_HISTORY_ENABLE_off" value="0" {if !$AI_HISTORY_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_HISTORY_ENABLE_off">{l s='No' mod='advancedimporter'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
				<p class="help-block">{l s='Track creation and update of objects.' mod='advancedimporter'}</p>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-lg-3" for="AI_LOG_ENABLE">{l s='Enable log: ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<div class="row">
					<div class="input-group col-lg-2">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="AI_LOG_ENABLE" id="AI_LOG_ENABLE_on" value="1" {if $AI_LOG_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_LOG_ENABLE_on">{l s='Yes' mod='advancedimporter'}</label>
							<input type="radio" name="AI_LOG_ENABLE" id="AI_LOG_ENABLE_off" value="0" {if !$AI_LOG_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_LOG_ENABLE_off">{l s='No' mod='advancedimporter'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
				<p class="help-block">{l s='If you don\'t use this feature, you can disable it.' mod='advancedimporter'}</p>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-lg-3" for="AI_SYSLOG_ENABLE">{l s='Enable system log: ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<div class="row">
					<div class="input-group col-lg-2">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="AI_SYSLOG_ENABLE" id="AI_SYSLOG_ENABLE_on" value="1" {if $AI_SYSLOG_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_SYSLOG_ENABLE_on">{l s='Yes' mod='advancedimporter'}</label>
							<input type="radio" name="AI_SYSLOG_ENABLE" id="AI_SYSLOG_ENABLE_off" value="0" {if !$AI_SYSLOG_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_SYSLOG_ENABLE_off">{l s='No' mod='advancedimporter'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
				<p class="help-block">{l s='If you don\'t use this feature, you can disable it.' mod='advancedimporter'}</p>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3" for="AI_NOTICELOG_ENABLE">{l s='Enable notice log: ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<div class="row">
					<div class="input-group col-lg-2">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="AI_NOTICELOG_ENABLE" id="AI_NOTICELOG_ENABLE_on" value="1" {if $AI_NOTICELOG_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_NOTICELOG_ENABLE_on">{l s='Yes' mod='advancedimporter'}</label>
							<input type="radio" name="AI_NOTICELOG_ENABLE" id="AI_NOTICELOG_ENABLE_off" value="0" {if !$AI_NOTICELOG_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_NOTICELOG_ENABLE_off">{l s='No' mod='advancedimporter'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
				<p class="help-block">{l s='If you don\'t use this feature, you can disable it.' mod='advancedimporter'}</p>
			</div>
		</div>

		<div class="form-group" class="form-group">
			<label class="control-label col-lg-3" for="AI_ERRORLOG_ENABLE">{l s='Enable error log: ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<div class="row">
					<div class="input-group col-lg-2">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="AI_ERRORLOG_ENABLE" id="AI_ERRORLOG_ENABLE_on" value="1" {if $AI_ERRORLOG_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_ERRORLOG_ENABLE_on">{l s='Yes' mod='advancedimporter'}</label>
							<input type="radio" name="AI_ERRORLOG_ENABLE" id="AI_ERRORLOG_ENABLE_off" value="0" {if !$AI_ERRORLOG_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_ERRORLOG_ENABLE_off">{l s='No' mod='advancedimporter'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
				<p class="help-block">{l s='If you don\'t use this feature, you can disable it.' mod='advancedimporter'}</p>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-lg-3" for="AI_SUCCESSLOG_ENABLE">{l s='Enable success log: ' mod='advancedimporter'}</label>
			<div class="col-lg-9">
				<div class="row">
					<div class="input-group col-lg-2">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="AI_SUCCESSLOG_ENABLE" id="AI_SUCCESSLOG_ENABLE_on" value="1" {if $AI_SUCCESSLOG_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_SUCCESSLOG_ENABLE_on">{l s='Yes' mod='advancedimporter'}</label>
							<input type="radio" name="AI_SUCCESSLOG_ENABLE" id="AI_SUCCESSLOG_ENABLE_off" value="0" {if !$AI_SUCCESSLOG_ENABLE}checked="checked"{/if}>
							<label class="radioCheck" for="AI_SUCCESSLOG_ENABLE_off">{l s='No' mod='advancedimporter'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
				<p class="help-block">{l s='If you don\'t use this feature, you can disable it.' mod='advancedimporter'}</p>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-lg-9 col-lg-offset-3">
				<input type="submit" id="advancedimporter_configuration_form_submit_btn" value="{l s='Save' mod='advancedimporter'}" name="submitAddadvancedimporter_configuration" class="btn btn-default">
			</div>
		</div>
	</fieldset>
</form>
{if isset($iframe)}
	<script src="{$iframe|escape:'htmlall':'UTF-8'}"></script>
{/if}
