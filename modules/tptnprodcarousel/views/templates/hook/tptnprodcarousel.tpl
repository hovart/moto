<div id="tptnprodcarousel" class="prodcrsl container">
	{if isset($categories) AND $categories}
	{foreach from=$categories item=category}
	<div class="tptncarousel clearfix">
		<div class="block_title"><span>{$category.name}</span></div>
		{if isset($category.products) AND $category.products}
		<div class="tptnslides row">
			{foreach from=$category.products item=product}
			<div class="ajax_block_product item col-xs-12" itemscope itemtype="http://schema.org/Product">
				<div class="item-content">
					<div class="left-block">
						<div class="product-image-container">
							<div class="first-image">
								<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
									<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />
								</a>
							</div>
							<div class="second-image">
								<a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">
									<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default', $product.id_product)|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} />
								</a>
							</div>
							{if isset($product.new) && $product.new == 1}<span class="new-box">{l s='New' mod='tptnprodcarousel'}</span>{/if}
							{if isset($product.reduction) && $product.reduction}<span class="sale-box">{l s='Sale!' mod='tptnprodcarousel'}</span>{/if}
						</div>
					</div>
					<div class="right-block">
						<h5 itemprop="name">
							{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
							<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
								{$product.name|truncate:30:'...'|escape:'html':'UTF-8'}
							</a>
						</h5>
						{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
						<div class="content_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
							{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
								<span itemprop="price" class="price product-price">
									{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
								</span>
								<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
								{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
									<span class="old-price product-price">
										{displayWtPrice p=$product.price_without_reduction}
									</span>
								{/if}
							{/if}
						</div>
						{/if}
						<div class="functional-buttons">
							<div class="button-container">
							{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
								{if ($product.allow_oosp || $product.quantity > 0)}
									{if isset($static_token)}
										<a class="ajax_add_to_cart_button simptip" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" data-tooltip="{l s='Add to cart' mod='tptnprodcarousel'}" data-id-product="{$product.id_product|intval}"><i class="fa fa-shopping-cart"></i></a>
									{else}
										<a class="ajax_add_to_cart_button simptip" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" data-tooltip="{l s='Add to cart' mod='tptnprodcarousel'}" data-id-product="{$product.id_product|intval}"><i class="fa fa-shopping-cart"></i></a>
									{/if}
								{else}
									<span class="ajax_add_to_cart_button disabled"><i class="fa fa-shopping-cart"></i></span>
								{/if}
							{/if}
							</div>
							<div class="quickview">
								<a class="quick-view simptip" href="{$product.link|escape:'html':'UTF-8'}" data-tooltip="{l s='Quick view' mod='tptnprodcarousel'}" rel="{$product.link|escape:'html':'UTF-8'}"><i class="fa fa-expand"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			{/foreach}
		</div>
		{else}
		<p>
			{l s='No products available in this category' mod='tptnprodcarousel'}
		</p>
		{/if}
	</div>
	{/foreach}
	{/if}
</div>