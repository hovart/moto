{if count($csoc_product_selection) > 0}










<div  class="tptncarousel prodcrsl clearfix">
	<div class="block_title"><span>

			{$csoc_bloc_title|escape:'html':'UTF-8'}

	</span></div>
	<div class="tptnslides row">
		{foreach from=$csoc_product_selection item='cartProduct' name=cartProduct}
		<div class="item col-xs-12">
			<div class="item-content">
				<div class="left-block">
					<div class="product-image-container">
						<a  href="{$link->getProductLink($cartProduct.id_product, $cartProduct.link_rewrite, $cartProduct.category)}" title="{$cartProduct.name|escape:'html':'UTF-8'}">

							{if empty($cartProduct.link_rewrite)}
								<img src="{$link->getImageLink("default", $cartProduct.id_image, $imageSize)}" alt="{$cartProduct.name|escape:'html':'UTF-8'}" />
							{else}
								<img src="{$link->getImageLink($cartProduct.link_rewrite, $cartProduct.id_image, $imageSize)}" alt="{$cartProduct.name|escape:'html':'UTF-8'}" />
							{/if}
							

							{if isset($cartProduct.new) && $cartProduct.new == 1}<span class="new-box">{l s='New' mod='tptnprodtabs'}</span>{/if}
							{if isset($cartProduct.specific_prices.reduction) && $cartProduct.specific_prices.reduction}<span class="sale-box">
								{strip}
									- {$cartProduct.specific_prices.reduction * 100}%
								{/strip}
								</span>{/if}
						</a>
					</div><!-- .product-image-container -->

					<div class="right-block">
							<div class="manufacturerContainer">
								<img class="manufacturer" src="{$img_manu_dir}{$cartProduct.id_manufacturer}.jpg" alt="{$cartProduct.manufacturer_name|escape:'htmlall':'UTF-8'}" title="{$cartProduct.manufacturer_name|escape:'htmlall':'UTF-8'}" />
							</div>
							<h5 itemprop="name">
								<a href="{$cartProduct.link|escape:'html':'UTF-8'}" title="{$cartProduct.name|escape:'html':'UTF-8'}" class="product-name" itemprop="name">
									{if isset($on_product_page) && $on_product_page}
										{$cartProduct.name|truncate:20:'...':true|escape:'html':'UTF-8'}
									{else}
										{$cartProduct.name|truncate:45:'...'|escape:'html':'UTF-8'}
									{/if}
								</a>
							</h5>

						<div class="content_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

							<span itemprop="price" class="price product-price">
								{if !$priceDisplay}{convertPrice price=$cartProduct.price}{else}{convertPrice price=$cartProduct.price_tax_exc}{/if}
							</span>
							<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
							{if isset($cartProduct.specific_prices) && $cartProduct.specific_prices && isset($cartProduct.specific_prices.reduction) && $cartProduct.specific_prices.reduction > 0}

								<span class="old-price product-price">
									{displayWtPrice p=$cartProduct.price_without_reduction}
								</span>
							{/if}

						</div>

						</div>
				</div><!-- .left-block -->


				{if !empty($csoc_display["{$csoc_prefix}_DISPLAY_BUTTON"])}
				<div class="button-container">
					{if ($cartProduct.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $cartProduct.available_for_order && !isset($restricted_country_mode) && $cartProduct.minimal_quantity <= 1 && $cartProduct.customizable != 2 && !$PS_CATALOG_MODE}
						{if ($cartProduct.allow_oosp || $cartProduct.quantity > 0)}
							{if isset($static_token)}
								<a class="button ajax_add_to_cart_button {if $csoc_prefix == 'PM_MC_CSOC'} button-small{/if} {if isset($on_product_page) && $on_product_page}exclusive{else}btn btn-default{/if}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$cartProduct.id_product|intval}&amp;id_product_attribute={$cartProduct.id_product_attribute|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='pm_crosssellingoncart'}" data-id-product="{$cartProduct.id_product|intval}" data-id-product-attribute="{$cartProduct.id_product_attribute|intval}">
									<span>{l s='Add to cart' mod='pm_crosssellingoncart'}</span>
								</a>
							{else}
								<a class="button ajax_add_to_cart_button{if $csoc_prefix == 'PM_MC_CSOC'} button-small{/if} {if isset($on_product_page) && $on_product_page}exclusive{else}btn btn-default{/if}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$cartProduct.id_product|intval}&amp;id_product_attribute={$cartProduct.id_product_attribute|intval}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='pm_crosssellingoncart'}" data-id-product="{$cartProduct.id_product|intval}" data-id-product-attribute="{$cartProduct.id_product_attribute|intval}">
									<span>{l s='Add to cart' mod='pm_crosssellingoncart'}</span>
								</a>
							{/if}
						{else}
							<span class="button ajax_add_to_cart_button{if $csoc_prefix == 'PM_MC_CSOC'} button-small{/if} disabled {if isset($on_product_page) && $on_product_page}exclusive{else}btn btn-default{/if}">
								<span>{l s='Add to cart' mod='pm_crosssellingoncart'}</span>
							</span>
						{/if}
					{/if}
				</div>
			</div>
				{/if}
				{*
				<div class="product-flags">
					{if (!$PS_CATALOG_MODE AND ((isset($cartProduct.show_price) && $cartProduct.show_price) || (isset($cartProduct.available_for_order) && $cartProduct.available_for_order)))}
						{if isset($cartProduct.online_only) && $cartProduct.online_only}
							<span class="online_only">{l s='Online only' mod='pm_crosssellingoncart'}</span>
						{/if}
					{/if}
					{if isset($cartProduct.on_sale) && $cartProduct.on_sale && isset($cartProduct.show_price) && $cartProduct.show_price && !$PS_CATALOG_MODE}
						{elseif isset($cartProduct.reduction) && $cartProduct.reduction && isset($cartProduct.show_price) && $cartProduct.show_price && !$PS_CATALOG_MODE}
							<span class="discount">{l s='Reduced price!' mod='pm_crosssellingoncart'}</span>
						{/if}
				</div>
				*}

		</div><!-- .product-container -->
		{/foreach}
	</div>
</div>

{/if}