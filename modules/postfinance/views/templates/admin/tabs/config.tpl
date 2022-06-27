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
<form role="form" class="form-inline" action="{$requestUri|escape:'htmlall':'UTF-8'}" method="post" id="form_postfinance">
	<div>
		<span id="alert_config_pf">{$alert_config}</span><!-- Escaped in the method that is called -->
		<h3>
			<i class="icon-AdminTools"></i> {l s='Configuration' mod='postfinance'} <small>{$module_display|escape:'htmlall':'UTF-8'}</small>
		</h3>
		<p><b>{l s='Welcome to the interface of your PostFinance page!' mod='postfinance'}</b></p>
		<p>{l s='This Setup tab allows you to fill in the identifiers associated with your Postfinance account.' mod='postfinance'}</p>
		<p>{l s='Please, note that in order to take full advantage of the functions offered by the Postfinance module, you need a PostFinance e-Payment subscription.' mod='postfinance'}</p>
		<p>{l s='See this' mod='postfinance'}&nbsp;<a href="https://www.postfinance.ch/en/biz/prod/eserv/epay/providing/offer.html" target="_blank">{l s='link' mod='postfinance'}</a>&nbsp;{l s='for further information.' mod='postfinance'}</p>
		<!-- Production/test Mode switch -->
		<div id="{$module_name|escape:'htmlall':'UTF-8'}_live_mode_switch_div">
			<p><b>{l s='Account Mode' mod='postfinance'}</b></p>
			<span id="live_mode" class="switch prestashop-switch input-group col-lg-3">
				<input type="radio" name="live_mode" id="production" {if $config.postfinance_LIVE_MODE eq 1}checked="checked"{/if} value="1" />
				<label for="production" class="radioCheck">
						<i class="color_success"></i> {l s='Production' mod='postfinance'}
				</label>
				<input type="radio" name="live_mode" id="test" {if $config.postfinance_LIVE_MODE eq 0}checked="checked"{/if} value="0"/>
				<label for="test" class="radioCheck">
						<i class="color_danger"></i> {l s='Test Mode' mod='postfinance'}
				</label>
				<a class="slide-button btn"></a>
			</span>
			<div class="clear">&nbsp;</div>
		</div>
		<!-- Account information -->
		<div class="col-sm-12 col-md-12 col-lg-12" id="{$module_name|escape:'htmlall':'UTF-8'}_account_info">
			<div class="clear">&nbsp;</div>
			<h3>
				<i class="icon-user"></i>&nbsp;{l s='Account Information' mod='postfinance'}
			</h3>
			<div class="form-group col-sm-12 col-md-12 col-lg-12">
				<label class="col-sm-12 col-md-6 col-lg-6" for="id_merchant">{$module_display|escape:'htmlall':'UTF-8'}&nbsp;{l s='Account ID' mod='postfinance'}&nbsp;(PSPID)</label>&nbsp;&nbsp;
				<input type="text" class="form-control col-sm-12 col-md-6 col-lg-6" id="id_merchant" name="id_merchant" value="{$config.postfinance_ID_MERCHANT|escape:'htmlall':'UTF-8'}">
			</div>
			<small>{l s='Sent to you by' mod='postfinance'}&nbsp;{$module_display|escape:'htmlall':'UTF-8'}</small>
			<div class="clear">&nbsp;</div>
			<div class="form-group col-sm-12 col-md-12 col-lg-12">
				<label class="col-sm-12 col-md-6 col-lg-6" for="sha_in_phrase">{$module_display|escape:'htmlall':'UTF-8'}&nbsp;{l s='SHA-IN pass phrase' mod='postfinance'}</label>&nbsp;&nbsp;
				<input type="text" class="form-control col-sm-12 col-md-6 col-lg-6" id="sha_in_phrase" name="sha_in_phrase" value="{$config.postfinance_SHA_IN|escape:'htmlall':'UTF-8'}">
			</div>
			<small>{l s='Same value that you have entered in your PostFinance configuration page' mod='postfinance'}</small>

			<div class="clear">&nbsp;</div>
			<div class="form-group col-sm-12 col-md-12 col-lg-12">
				<label class="col-sm-12 col-md-6 col-lg-6" for="sha_out_phrase">{$module_display|escape:'htmlall':'UTF-8'}&nbsp;{l s='SHA-OUT pass phrase' mod='postfinance'}</label>&nbsp;&nbsp;
				<input type="text" class="form-control col-sm-12 col-md-6 col-lg-6" id="sha_out_phrase" name="sha_out_phrase" value="{$config.postfinance_SHA_OUT|escape:'htmlall':'UTF-8'}">
			</div>
			<small>{l s='Same value that you have entered in your PostFinance configuration page' mod='postfinance'}</small>
			<div class="clear">&nbsp;</div>
			<div id="postfinance_bo_config" class="well col-sm-12 col-md-12 col-lg-12">
				<i style="float:left; padding: 0px 10px 0px 3px;" class="icon-exclamation-triangle icon-3x"></i>&nbsp;<b>{l s='Important' mod='postfinance'}</b>&nbsp;:&nbsp;{l s='In your PostFinance interface, please tick the checkbox to receive transaction feedback. You can find this in Configuration -> Technical Information -> Transaction Feedback' mod='postfinance'}
			<div class="clear">&nbsp;</div>
				&nbsp;{l s='Also in your PostFinance interface, Configuration -> Technical Information -> Transaction Feedback please verify that the list of Dynamic e-Commerce parameters contains the following parameters, and only these parameters' mod='postfinance'}
				<ul>
					<li>ACCEPTANCE</li>
					<li>AMOUNT</li>
					<li>BRAND</li>
					<li>CARDNO</li>
					<li>CN</li>
					<li>COMPLUS</li>
					<li>CURRENCY</li>
					<li>ED</li>
					<li>IP</li>
					<li>NCERROR</li>
					<li>ORDERID</li>
					<li>PAYID</li>
					<li>PM</li>
					<li>STATUS</li>
					<li>TRXDATE</li>
				</ul>
			</div>
		</div>
		<div class="clear">&nbsp;</div>
		{include file="./paymentPageLayout.tpl"}
		<div class="clear">&nbsp;</div>
		{include file="./paymentPageLogo.tpl"}
		<input type="hidden" id="check_submit_pf" name="check_submit_pf" value="yes">
		<center><Button type="button" name="submit{$module_name|escape:'htmlall':'UTF-8'}" id="submit{$module_name|escape:'htmlall':'UTF-8'}" class="btn btn-info" onclick="submitConfigForm();" />{l s='Update settings' mod='postfinance'}</button></center>
	</div>
</form>