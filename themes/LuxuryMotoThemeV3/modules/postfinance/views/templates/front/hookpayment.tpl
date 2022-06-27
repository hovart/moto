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
<div id="{$module_name|escape:'htmlall':'UTF-8'}">
	<p class="payment_module">
		<a name class="bankwire" href="javascript:document.{$module_name|escape:'htmlall':'UTF-8'}_form.submit();" title="{$payment_text|escape:'htmlall':'UTF-8'}">
			<img src="{$payment_logo|escape:'htmlall':'UTF-8'}" style="margin-right:20px;" alt="{$payment_text|escape:'htmlall':'UTF-8'}" />
			{if $lang_iso == de }
				<button class="pay-button button pull-right"> Weiter zur Bezahlung <i class="fa fa-chevron-right right"></i> </button>
			{else}
				<button class="pay-button button pull-right"> Procéder au paiement <i class="fa fa-chevron-right right"></i> </button>
			{/if}
		</a>
	</p>
	<form action="{$postfinance_link|escape:'htmlall':'UTF-8'}" method="post" name="{$module_name|escape:'htmlall':'UTF-8'}_form">
		{foreach from=$params key=k item=v}
			<input type="hidden" name="{$k|escape:'htmlall':'UTF-8'}" value="{$v|escape:'htmlall':'UTF-8'}" />
		{/foreach}
	</form>
</div>
