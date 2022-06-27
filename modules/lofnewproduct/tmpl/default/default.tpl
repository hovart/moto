<div class="clearfix clear clr"></div>
<div class="lof-newproduct {$theme}">
	<div class="newproduct-widget block">
		<div class="header">
			<h4 class="newproduct-title title_block">{l s='New product' mod='lofnewproduct'}</h4>
		</div>
		<div class="list-newproduct responsive">
			<ul id="lofnewproduct-{$moduleId}" class="newproduct-news clearfix">
				{foreach from=$listNews item=item}    
				<li>
					<div class="article lof-content">
						<div class="newproduct-item box-hover clearfix">
							{if $show_title eq '1'}
							<h5 class="entry-title">
								<a href="{$item.link}" target="{$target}">{$item.name}</a>
							</h5>
							{/if}
							<div class="video-thumb lof-product">
							<a href="{$item.link}" title="{$item.name|escape:html:'UTF-8'}" class="product_image">
								<img class="responsive-img" src="{$item.mainImge}" alt="{$item.name}"/>
							</a>
							</div>
							{if $show_desc eq '1'}
								<p class="product_desc">{$item.description}</p>
							{/if}
							{if $show_price eq '1'}
								<p class="entry-price price_container"><span class="price lof-price">{$item.price}</span></p>
							{/if}

							{if (($item.reduction) != ($item.price)) AND ($priceSpecial  eq '1')}
							<div class="entry-price-discount">{displayWtPrice p=$item.price_without_reduction}</div>
							{/if}
							<div class="entry-content lof-button">
								{if (($item.quantity > 0 OR $item.allow_oosp))}
								<a class="lof-add-cart ajax_add_to_cart_button" rel="ajax_id_product_{$item.id_product}" href="{$site_url}cart.php?add&amp;id_product={$item.id_product}&amp;token={$token}"><span>{l s='Add to cart' mod='lofnewproduct'}</span></a>
								{else}
									<span class="lof-add-cart">{l s='Add to cart' mod='lofnewproduct'}</span></a>
								{/if}
								<a href="{$item.link}" class="lof-detail">{l s='Detail' mod='lofnewproduct'}</a>
							</div>
						</div>
					</div>				
				</li>
				{/foreach}
			</ul>		
			<div class="clear"></div>
			{if $show_button eq '1'}
			<div class="newproduct-nav">
				<a id="lofprev-{$moduleId}" class="prev" href="#">&nbsp;</a>
				<a id="lofnext-{$moduleId}" class="next" href="#">&nbsp;</a>
			</div>{/if}
			{if $show_pager eq '1'}<div id="lofpager-{$moduleId}" class="lof-pager"></div>{/if}
		</div>
	</div>
</div>
<script type="text/javascript">
// <![CDATA[
			$('#lofnewproduct-{$moduleId}').carouFredSel({ldelim}
				responsive:true,
				prev: '#lofprev-{$moduleId}',
				next: '#lofnext-{$moduleId}',
				pagination: "#lofpager-{$moduleId}",
				auto: {$auto_play},
				width: {$slide_width},
				height: {$slide_height},
				scroll: {$scroll_items},
				items:{ldelim}
					width:200,
					visible:{ldelim}
						min:1,
						max:{$limit_cols}
					{rdelim}
				{rdelim}
			{rdelim});	

// ]]>
</script>  
