<div class="ajax_block_product item">
	<a class="product_img_link" href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}">
		<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" height="{$homeSize.height}" width="{$homeSize.width}" alt="{$product.name|escape:html:'UTF-8'}" />
	</a>
	
	{if isset($product.new) && $product.new == 1}<span class="new tag">{l s='New' mod='tptnprodcarousel'}</span>{/if}
	{if isset($product.reduction) && $product.reduction}<span class="reduction tag">{l s='Sale' mod='tptnprodcarousel'}</span>{/if}
	
	<p id="logomarque">{if $product.id_manufacturer != 0}
	<img src="{$img_ps_dir}m/{$product.id_manufacturer}-medium_default.jpg" alt="" />
	{/if}</p>
	
	<a class="prod_name" href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}">{$product.name|truncate:28:'...'|escape:html:'UTF-8'}</a>
	<div class="product_desc">{$product.description_short|strip_tags:'UTF-8'|truncate:70:'...'}</div>
	{if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
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
	<div class="container-button">
	{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
		{if ($product.allow_oosp || $product.quantity > 0)}
			{if isset($static_token)}
				<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)}" title="{l s='Add to cart' mod='tptnprodcarousel'}">{l s='Add to cart' mod='tptnprodcarousel'}</a>
			{else}
				<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}", false)}" title="{l s='Add to cart' mod='tptnprodcarousel'}">{l s='Add to cart' mod='tptnprodcarousel'}</a>
			{/if}						
		{else}
			<span class="exclusive">{l s='Add to cart' mod='tptnprodcarousel'}</span>
		{/if}
	{/if}
	</div>
</div>