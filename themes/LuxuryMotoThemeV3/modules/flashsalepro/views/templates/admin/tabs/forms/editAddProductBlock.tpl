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

<div id="edit_sale_add_product_{$product.id_product|escape:'htmlall':'UTF-8'}" class="well col-sm-12 col-md-12 col-lg-12">
    <div class="col-sm-12 col-md-6 col-lg-6">
    	<center>
	    	<img src="{$product.image|escape:'htmlall':'UTF-8'}" class="product-block-img_edit">
	        	<p><b>{$product.name|escape:'htmlall':'UTF-8'}</b></p>
    	</center>
	</div>
	<div class="col-sm-12 col-md-6 col-lg-6">
		    <div class="input-group flash-sale-amount-div">
		    	<span class="input-group-btn dropdown">
		    		<button class="btn btn-default dropdown-toggle" type="button" id="{$product.id_product|escape:'htmlall':'UTF-8'}add_product_discount_type_symbol_button" data-toggle="dropdown" aria-expanded="true">
                        <span id="{$product.id_product|escape:'htmlall':'UTF-8'}add_product_discount_type_symbol">{$currency_symbol|escape:'htmlall':'UTF-8'}</span>&nbsp;&nbsp;<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuFlashSaleDiscountType">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" onclick="update_edit_discount_type_div_class('amount', {$product.id_product|escape:'htmlall':'UTF-8'}, {$currency_symbol|escape:'htmlall':'UTF-8'});" value="amount">{$currency_symbol|escape:'htmlall':'UTF-8'}</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" onclick="update_edit_discount_type_div_class('percentage', {$product.id_product|escape:'htmlall':'UTF-8'}, '%');" value="percentage">%</a></li>
                    </ul>
		    	</span>
		    	<input type="hidden" id="{$product.id_product|escape:'htmlall':'UTF-8'}_edit_flash_sale_discount_type" name="{$product.id_product|escape:'htmlall':'UTF-8'}_edit_flash_sale_discount_type" value="amount">

				<input class="form-control flash-sale-amount-input" placeholder="{l s='Amount' mod='flashsalepro'}" type="text" id="amount_add_product{$product.id_product|escape:'htmlall':'UTF-8'}" name="amount_add_product{$product.id_product|escape:'htmlall':'UTF-8'}">
				<span class="input-group-btn">
					<button type="type" class="btn btn-default" onclick="addProductToSale({$product.id_product|escape:'htmlall':'UTF-8'});">{l s='Add' mod='flashsalepro'}</button>
				</span>
			</div>
	</div>
</div>
{literal}
<script>
 var added_alert_text = "{/literal}{l s='Product Added' mod='flashsalepro' js=1}{literal}";
</script>
{/literal}