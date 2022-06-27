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

<div id="flash_sale_confirmation_info_div">
    <div class="clear">&nbsp;</div>

	<div id="flash_sale_confirmation_name_div">
		<h3 id="flash_sale_confirmation_name"></h3>
	</div>
	<div id="flash_sale_confirmation_date_from_div">
		<p><b>{l s='From' mod='flashsalepro'}&nbsp;:</b>&nbsp;&nbsp;<span id="flash_sale_confirmation_date_from"></span>&nbsp;&nbsp;
		<b>{l s='To' mod='flashsalepro'}&nbsp;:</b>&nbsp;&nbsp;<span id="flash_sale_confirmation_date_to"></span></p>
	</div>
	<div id="flash_sale_confirmation_type_div">
		<p><b>{l s='Flash Sale Type' mod='flashsalepro'}&nbsp;:</b>&nbsp;&nbsp;<span id="flash_sale_confirmation_type"></span></p>
	</div>
	<div id="flash_sale_confirmation_group_div">
		<p><b>{l s='Group Restriction' mod='flashsalepro'}&nbsp;:</b>&nbsp;&nbsp;<span id="flash_sale_confirmation_group"></span></p>
	</div>
	<div id="flash_sale_confirmation_currency_div">
		<p><b>{l s='Currency Restriction' mod='flashsalepro'}&nbsp;:</b>&nbsp;&nbsp;<span id="flash_sale_confirmation_currency"></span></p>
	</div>
	<div id="flash_sale_confirmation_country_div">
		<p><b>{l s='Country Restriction' mod='flashsalepro'}&nbsp;:</b>&nbsp;&nbsp;<span id="flash_sale_confirmation_country"></span></p>
	</div>
</div>

<div>
	<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-hover" id="confirmation_table" name="confirmation_table">
		<tr>
			<th>{l s='Product ID' mod='flashsalepro'}</th>
			<th>{l s='Product Name' mod='flashsalepro'}</th>
			<th>{l s='Discount' mod='flashsalepro'}</th>
			<th>{l s='Discount Type' mod='flashsalepro'}</th>
			<th></th>
		</tr>
		<tbody id="flash_sale_confirmation_product_table_body">
			
		</tbody>
	</table>
</div>
