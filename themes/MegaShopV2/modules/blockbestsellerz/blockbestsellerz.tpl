<!-- MODULE Block best sellers -->
<div id="viewed-products_block_left" class="block products_block">
	<h4><a href="{$base_dir}best-sales.php">{l s='Top sellers' mod='blockbestsellerz'}</a></h4>
	<div class="block_content">
	{if $best_sellers != false}    
		<ul class="products clearfix">
		{foreach from=$best_sellers item=product name=myLoop}
				<li class="clearfix{if $smarty.foreach.myLoop.last} last_item{elseif $smarty.foreach.myLoop.first} first_item{else} item{/if}">
					<a href="{$product.link}" title="{$product.legend|escape:html:'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" height="{$mediumSize.height}" width="{$mediumSize.width}" alt="{$product.legend|escape:html:'UTF-8'}" /></a>

				<div class="content_name">
                    <a href="{$product.link|escape:'html'}" title="{$product.name|escape:'htmlall':'UTF-8'}" class="prod_name">{$product.name|truncate:35:'...'|strip_tags:'UTF-8'|escape:'htmlall':'UTF-8'}</a>
                    {if !$PS_CATALOG_MODE}
                        <div class="price">
                            {if !$priceDisplay}
                                {convertPrice price=$product.price}
                            {else}
                                {convertPrice price=$product.price_tax_exc}
                            {/if}
                        </div>
                        {if isset($product.reduction) && $product.reduction}
                            <div class="prod_price_discount">{convertPrice price=$product.price_without_reduction}</div>
                        {/if}
                    {/if}	
                </div>	

        </li>		
		{/foreach}
		</ul>
		<p><a href="{$base_dir}best-sales.php" title="{l s='All best sellers' mod='blockbestsellerz'}" class="button_large">{l s='All best sellers' mod='blockbestsellerz'}</a></p>
	{else}
		<p>{l s='No best sellers at this time' mod='blockbestsellerz'}</p>
	{/if}
	</div>
</div>
<!-- /MODULE Block best sellers -->
