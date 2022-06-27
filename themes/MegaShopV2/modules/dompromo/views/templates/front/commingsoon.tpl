<!-- MODULE {$moduleinfo}-->
{capture name=path}{l s='Comming soon Price drop' mod='dompromo'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Comming soon Price drop' mod='dompromo'}</h2>
{if isset($products) && $products}
	<!-- Products list -->
	<ul id="product_list" class="clear">		
		{foreach from=$products item=product name=products}
			<li class="ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if} {if $smarty.foreach.products.index % 2}alternate_item{else}item{/if} clearfix">
				<div class="center_block">
					<a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, $tailleimg)}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" /></a><span style="color:green;font-style:italic;font-weight:bold;"><br />
					<h3>{if $product.new == 1}<span class="new">{l s='new' mod='dompromo'}</span>{/if}<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.legend|escape:'htmlall':'UTF-8'}">{$product.name|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a></h3>
					<p class="product_desc"><a href="{$product.link|escape:'htmlall':'UTF-8'}">{$product.description_short|strip_tags:'UTF-8'|truncate:120:'...'}</a></p>
				</div>

				<div class="right_block">
					{if $product.on_sale==1}
						<span class="discount">{l s='On sale!' mod='dompromo'}</span>				
					{elseif $product.typesale==1}
						<span class="discount">{l s='Flash Sale' mod='dompromo'}</span>
					{elseif  $product.typesale==2}
				    <span class="discount">{l s='Coutant Price' mod='dompromo'}</span>
					{elseif  $product.typesale==3}
				    <span class="discount">{l s='Reduction of Stocks' mod='dompromo'}</span>
					{elseif $product.on_sale==0}
						<span class="discount">{l s='Specials' mod='dompromo'}</span>
					{/if}
					<br />

					<div class="dpoldprix" style="color:#{$commingtextstartColor};font-weight:bold;text-align:left; "> {l s='From:' mod='dompromo'}</div>
					<div style="color:#{$commingdateColor};">
						{if $product.typesale==1 or $product.typesale==2 or $product.typesale==3}
							{$product.datedebut|date_format:"%A, %d %B"} {l s='at' mod='dompromo'} {$product.datedebut|date_format:"%X"}
						{else}
							{$product.reduction_from|date_format:'%A, %d %B'}
						{/if}
					</div>
					
					<div class="dpoldprix" style="color:#{$commingtextfinishColor};font-weight:bold;text-align:justify; ">{l s='To:' mod='dompromo'}</div>
					<div style="color:#{$commingdateColor};">
						{if $product.typesale==1 or $product.typesale==2 or $product.typesale==3}
							{$product.datefin|date_format:'%A, %d %B'} {l s='at' mod='dompromo'} {$product.datefin|date_format:"%X"}
						{else}
							{$product.reduction_to|date_format:'%A, %d %B'}
						{/if}
					</div>
				
				<a class="button lnk_view" href="{$product.link|escape:'htmlall':'UTF-8'}" title="{l s='View'}">{l s='View' mod='dompromo'}</a>
			</div>
   <br class="clear"/>
		</li>
	{/foreach}
	</ul>
	<!-- /Products list -->

	<div class="content_sortPagiBar">
		{include file="$tpl_dir./pagination.tpl"}
	</div>
{else}
	<p class="warning">{l s='No Comming soon Price drop .' mod='dompromo'}</p>
{/if}

<!-- /MODULE {$moduleinfo} -->
















