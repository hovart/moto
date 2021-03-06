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
{if count($categoryProducts) > 0 && $categoryProducts !== false}
<div class="tptncarousel prodcrsl clearfix">
	<div class="block_title"><span>
		{if $categoryProducts|@count == 1}
			{l s='%s other product in the same category:' sprintf=[$categoryProducts|@count] mod='productscategory'}
		{else}
			{l s='%s other products in the same category:' sprintf=[$categoryProducts|@count] mod='productscategory'}
		{/if}
	</span></div>
	<div class="tptnslides row">
		{foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
			<div class="item col-xs-12">
			<div class="item-content">
				<div class="left-block">
					<div class="product-image">
						<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" title="{$categoryProduct.name|htmlspecialchars}"><img src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$categoryProduct.name|htmlspecialchars}" /></a>
					</div>
					<p class="product-name" itemprop="name">
						<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)|escape:'html':'UTF-8'}" title="{$categoryProduct.name|htmlspecialchars}">{$categoryProduct.name|truncate:30:'...'|escape:'html':'UTF-8'}</a>
					</p>
					{if $ProdDisplayPrice && $categoryProduct.show_price == 1 && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
						<p class="price_display">
						{if isset($categoryProduct.specific_prices) && $categoryProduct.specific_prices
						&& ($categoryProduct.displayed_price|number_format:2 !== $categoryProduct.price_without_reduction|number_format:2)}

							<span class="price special-price">{convertPrice price=$categoryProduct.displayed_price}</span>
							<span class="old-price">{displayWtPrice p=$categoryProduct.price_without_reduction}</span>

						{else}
							<span class="price">{convertPrice price=$categoryProduct.displayed_price}</span>
						{/if}
						</p>
					{/if}
					<div class="cart-btn">
					{if !$PS_CATALOG_MODE && ($categoryProduct.allow_oosp || $categoryProduct.quantity > 0)}
						<div class="no-print">
							<a class="exclusive button ajax_add_to_cart_button" href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$categoryProduct.id_product|intval}&amp;token={$static_token}&amp;add")|escape:'html':'UTF-8'}" data-id-product="{$categoryProduct.id_product|intval}" title="{l s='Add to cart' mod='productscategory'}">
								{l s='Add to cart' mod='productscategory'}
							</a>
						</div>
					{/if}
					</div>
				</div>
			</div>
			</div>
		{/foreach}
	</div>
</div>
{/if}