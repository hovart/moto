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
{if $status == 'ok'}
	<p>{l s='Your order on' mod='postfinance'} <span class="bold">{$shop_name|escape:'htmlall':'UTF-8'}</span> {l s='is complete.' mod='postfinance'}
		<br /><br /><span class="bold">{l s='Order ID :' mod='postfinance'}</span>&nbsp;<span>{$id_order|escape:'htmlall':'UTF-8'}</span>
		<br /><br /><span class="bold">{l s='Your order will be sent as soon as possible.' mod='postfinance'}</span>
		<br /><br />{l s='For any questions or for further information, please contact our' mod='postfinance'} <a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}{$contact_form|escape:'htmlall':'UTF-8'}">{l s='customer support' mod='postfinance'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='We noticed a problem with your order. If you think this is an error, you can contact our' mod='postfinance'} 
		<a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}{$contact_form|escape:'htmlall':'UTF-8'}">{l s='customer support' mod='postfinance'}</a>.
	</p>
{/if}
