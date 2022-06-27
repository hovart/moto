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
<tr id="row_item_'.$item['id_product'].'">';
	<td></td>';
	<td>{$id_product|escape:'htmlall':'UTF-8'}</td>
	<td>{$product_name|escape:'htmlall':'UTF-8'}</td>
	<td>{$discount|escape:'htmlall':'UTF-8'}</td>
	<td>{$discount_type|escape:'htmlall':'UTF-8'}</td>
	{if $sale_type eq "stock"}
		<td>
		{if $stock_status eq 1}
			<i class="icon-check icon-2x"></i>
		{else}
			<i class="icon-remove icon-2x"></i>
		{/if}
		</td>
	{/if}
</tr>