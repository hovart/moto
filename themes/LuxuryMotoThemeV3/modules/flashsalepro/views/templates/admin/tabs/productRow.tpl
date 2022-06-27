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
<tr id="row_product_{$id_product|escape:'htmlall':'UTF-8'}">
	<td>{$id_product|escape:'htmlall':'UTF-8'}</td>
	<td><img src="{$image|escape:'htmlall':'UTF-8'}" style="height:auto; width:auto; max-width:100px; max-height:100px;"></td>
	<td>{$product_name|escape:'htmlall':'UTF-8'}</td>
	<td>
		<div class="row">
			<div id="flash_type_div" style="float:left;width:53px;">
			    <div class="btn-group" data-toggle="buttons">
			        <label for="product_{$id_product|escape:'htmlall':'UTF-8'}_discount_type_amount" class="btn btn-info" id="product_{$id_product|escape:'htmlall':'UTF-8'}_discount_type_amount_label" onclick="update_payment_type_div_class('product_{$id_product|escape:'htmlall':'UTF-8'}_discount_type_amount_label', {$id_product|escape:'intval'}, 'product');">
			                <input type="radio" name="product_{$id_product|escape:'htmlall':'UTF-8'}_discount_type" id="product_{$id_product|escape:'htmlall':'UTF-8'}_discount_type_amount" value="amount" checked='checked'>{$default_currency_sign|escape:'htmlall':'UTF-8'}
			        </label>
			        <label for="product_{$id_product|escape:'htmlall':'UTF-8'}_discount_type_percent" class="btn btn-default" id="product_{$id_product|escape:'htmlall':'UTF-8'}_discount_type_percent_label" onclick="update_payment_type_div_class('product_{$id_product|escape:'htmlall':'UTF-8'}_discount_type_percent_label', {$id_product|escape:'intval'}, 'product');">
			                <input type="radio" name="product_{$id_product|escape:'htmlall':'UTF-8'}_discount_type" id="product_{$id_product|escape:'htmlall':'UTF-8'}_discount_type_percent" value="percentage">%
			        </label>
			    </div>
			</div>
			<div class="input-group flash-sale-amount-div">
				<div class="input-group-addon" id="{$id_product|escape:'htmlall':'UTF-8'}product_discount_type_symbol">{$default_currency_sign|escape:'htmlall':'UTF-8'}</div>
					<input class="form-control flash-sale-amount-input" placeholder="{l s='Amount' mod='flashsalepro'}" type="text" id="amount_product{$id_product|escape:'htmlall':'UTF-8'}" name="amount_product{$id_product|escape:'htmlall':'UTF-8'}"onblur="insertItemDiscountToDB({$id_product|escape:'intval'}, 'product')">
					<input type="hidden" id="id_product{$id_product|escape:'htmlall':'UTF-8'}" name="id_product{$id_product|escape:'htmlall':'UTF-8'}" value="{$id_product|escape:'htmlall':'UTF-8'}">
				</div>
			</div>
		</div>
	</td>
	<td><i class="icon-times" onClick="removeItemFromTable({$id_product|escape:'intval'}, 'product')" style="color:red;cursor:pointer;"></i></td>
</tr>
{if $flash_type eq 'stock'}
	<tr id="row_product_stock_{$id_product|escape:'htmlall':'UTF-8'}">
		<td colspan="5">
				<span class="col-sm-12 col-md-12 col-lg-12"><label>{l s='When stock level is' mod='flashsalepro'}&nbsp;</label></span>
				<label for="flashsale_stock_above_{$id_product|escape:'htmlall':'UTF-8'}">{l s='above' mod='flashsalepro'}&nbsp;</label>
				<input type="text" name="flashsale_stock_above_{$id_product|escape:'htmlall':'UTF-8'}" id="flashsale_stock_above_{$id_product|escape:'htmlall':'UTF-8'}" size="4">
				<label for="flashsale_stock_below_{$id_product|escape:'htmlall':'UTF-8'}">&nbsp;{l s='and below' mod='flashsalepro'}&nbsp;</label>
				<input type="text" name="flashsale_stock_below_{$id_product|escape:'htmlall':'UTF-8'}" id="flashsale_stock_below_{$id_product|escape:'htmlall':'UTF-8'}" size="4">
		</td>
	</tr>
{/if}
<tr id="row_product_image_{$id_product|escape:'htmlall':'UTF-8'}">
	<td colspan="5">
			<label for="custom_flashsale_img_{$id_product|escape:'htmlall':'UTF-8'}">{l s='Choose a custom logo for flash sale product' mod='flashsalepro'}&nbsp;</label>
			<small style="color:red;">&nbsp;({l s='Optional' mod='flashsalepro'})</small>
			<input type="file" name="custom_flashsale_img_{$id_product|escape:'htmlall':'UTF-8'}" id="custom_flashsale_img_{$id_product|escape:'htmlall':'UTF-8'}">
            <small>{l s='Recommended size: 105px X 50px' mod='flashsalepro'}</small>
	</td>
</tr>