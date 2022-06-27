{*
* 2007-2013 PrestaShop
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
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if isset($products)}
	<ul id="product_list" class="prodlist clear">
	{foreach from=$products item=product name=products}

		<li class="ajax_block_product item clearfix">

	{if $product.on_sale}
	<img class="onsale-list" src="{$img_dir}onsale_list_{$lang_iso}.png" alt="{l s='On sale'}" class="on_sale_img"/>
	{/if} 

			<a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
				<img class="img_first" style="display:block;" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html'}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} />

				{if $product.id_image2 == 0}
					<img class="img_second" style="display:none" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html'}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} />
				{else}
					<img class="img_second" style="display:none" src="{$link->getImageLink($product.link_rewrite, $product.id_image2, 'home_default')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} />
				{/if} 
			</a>
			
			{if isset($product.new) && $product.new == 1}<span class="new tag">{l s='New'}</span>{/if}
			{if isset($product.reduction) && $product.reduction}<span class="reduction tag">-{round($product.specific_prices.reduction * 100)}%</span>{/if}
			
			<p id="logomarque">{if $product.id_manufacturer != 0}
			<img src="{$img_ps_dir}m/{$product.id_manufacturer}-medium_default.jpg" alt="" />
			{/if}</p>
			
			{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}<a class="prod_name" href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}">{$product.name|truncate:30:'...'|escape:'htmlall':'UTF-8'}</a>
			
			<div class="product_desc">{$product.description_short|strip_tags:'UTF-8'|truncate:70:'...'}</div>
			{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order))) && !isset($restricted_country_mode)}
				<div class="price-content">
					<div class="price">
						{if !$priceDisplay}
							{convertPrice price=$product.price}
						{else}
							{convertPrice price=$product.price_tax_exc}
						{/if}
					</div>
					{if isset($product.reduction) && $product.reduction}
						<div class="price-discount">{convertPrice price=$product.price_without_reduction}</div>
					{/if}
				</div>
			{/if}
			
			{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
				{if ($product.allow_oosp || $product.quantity > 0)}
					<div class="container-button">
					{if isset($static_token)}
						<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html'}" title="{l s='Add to cart'}">{l s='Add to cart'}</a>
					{else}
						<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}", false)|escape:'html'}" title="{l s='Add to cart'}">{l s='Add to cart'}</a>
					{/if}
					</div>
				{else}
					<span class="exclusive">{l s='Add to cart'}</span>
				{/if}
			{/if}
			
			{if isset($comparator_max_item) && $comparator_max_item}
				<p class="compare">
					<input type="checkbox" class="comparator" id="comparator_item_{$product.id_product}" value="comparator_item_{$product.id_product}" {if isset($compareProducts) && in_array($product.id_product, $compareProducts)}checked="checked"{/if} autocomplete="off"/> 
					<label for="comparator_item_{$product.id_product}">{l s='Select to compare'}</label>
				</p>
			{/if}
		</li>
	{/foreach}
	</ul>
{/if}

<script type="text/javascript">
	$(".product_img_link").hover(
	function () {
		$(this).find('.img_second').toggle();
		$(this).find('.img_first').toggle();
		
		
	});
</script>