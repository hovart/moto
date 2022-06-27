{*
* 2007-2012 PrestaShop 
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

{if count($categoryProducts) > 0 && $categoryProducts !== false}
<div class="clearfix tptncarousel blockproductscategory">
	<h4 class ="title_block"><span>{l s='Related products' mod='productscategory'}</span></h4>
	<div class="rtslides prodlist">
		{foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
			<div class="item">
				<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" class="product_img_link" title="{$categoryProduct.name|htmlspecialchars}"><img src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'home_default')}" alt="{$categoryProduct.name|htmlspecialchars}" /></a>
				{if isset($categoryProduct.new) && $categoryProduct.new == 1}<span class="new tag">{l s='New' mod='productscategory'}</span>{/if}
				{if isset($categoryProduct.reduction) && $categoryProduct.reduction}<span class="reduction tag">-{round($categoryProduct.specific_prices.reduction * 100)}%</span>{/if}
				
				<p id="logomarque">{if $categoryProduct.id_manufacturer != 0}
				<img src="{$img_ps_dir}m/{$categoryProduct.id_manufacturer}-medium_default.jpg" alt="" />
				{/if}</p>
				
				<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" class="prod_name" title="{$categoryProduct.name|htmlspecialchars}">{$categoryProduct.name|truncate:30:'...'|escape:'htmlall':'UTF-8'}</a>
				<div class="product_desc">{$categoryProduct.description_short|strip_tags:'UTF-8'|truncate:65:'...'}</div>
				{if $ProdDisplayPrice AND $categoryProduct.show_price == 1 AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
					<div class="price-content">
						<div class="price">
							
								{convertPrice price=$categoryProduct.price}
							
								{convertPrice price=$categoryProduct.price_tax_exc}
							
						</div>
						{if isset($categoryProduct.reduction) && $categoryProduct.reduction}
							<div class="price-discount">{convertPrice price=$categoryProduct.price_without_reduction}</div>
						{/if}
					</div>	
				{/if}
				
				<!--{if ($categoryProduct.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $categoryProduct.available_for_order && !isset($restricted_country_mode) && $categoryProduct.minimal_quantity <= 1 && $categoryProduct.customizable != 2 && !$PS_CATALOG_MODE}
					<div class="container-button">
					{if ($categoryProduct.allow_oosp || $categoryProduct.quantity > 0)}
						{if isset($static_token)}
							<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$categoryProduct.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$categoryProduct.id_product|intval}&amp;token={$static_token}", false)}" title="{l s='Add to cart' mod='productscategory'}">{l s='Add to cart' mod='productscategory'}</a>
						{else}
							<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$categoryProduct.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$categoryProduct.id_product|intval}", false)}" title="{l s='Add to cart' mod='productscategory'}">{l s='Add to cart' mod='productscategory'}</a>
						{/if}						
					{else}
						<span class="exclusive">{l s='Add to cart' mod='productscategory'}</span>
					{/if}
					</div>
				{/if}
				-->
			</div>
		{/foreach}
	</div>	
</div>
{/if}
