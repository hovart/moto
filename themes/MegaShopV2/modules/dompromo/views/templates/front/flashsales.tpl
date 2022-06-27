<!-- MODULE {$moduleinfo} -->
<!-- {l s='Price drop' mod='dompromo'} permet d'apparaitre dans l'outil de traduction !!! -->
{$timer}
{capture name=path}{l s=$titre mod='dompromo'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
{assign var='compare' value='0'}

<h2>{l s=$titre mod='dompromo'}</h2>
{if isset($products) && $products}
	<!-- Products list -->
	<div class="content_sortPagiBar">
		<div class="sortPagiBar clearfix">
			{include file="$tpl_dir./product-sort.tpl"}
			{if $compare==1}{include file="$tpl_dir./product-compare.tpl"}{/if}
		</div>
	</div>

	<ul id="product_list" class="clear">
		{foreach from=$products item=product name=products}
			{if $product.typesale==1 || $product.typesale==2 || $product.typesale==3}
				{date_diff date1=$smarty.now|date_format:"%m/%d/%Y %X" date2=$product.datefin|date_format:"%m/%d/%Y %X" interval="minutes" assign="ddiff"}
				<script type="text/javascript">
				var cd{$product.id} = new countdown('cd{$product.id}');
				cd{$product.id}.Div	= "vftimer{$product.id}";
				cd{$product.id}.TargetDate	 ="{$product.datefin|date_format:"%m/%d/%Y %X"}";
				</script>
				{if $ddiff >= 1440}
					<script type="text/javascript">
						cd{$product.id}.DisplayFormat = "%%D%%{$vfd} %%H%%{$vfh} %%M%%{$vfm} %%S%%{$vfs}";
					</script>
				{elseif $ddiff > 60 AND $ddiff < 1440}
					<script type="text/javascript">
					cd{$product.id}.DisplayFormat = "%%H%%{$vfh} %%M%%{$vfm} %%S%%{$vfs}";
					</script>
				{elseif $ddiff < 60 AND $ddiff >= 1}
					<script type="text/javascript">
						cd{$product.id}.DisplayFormat = "%%M%%{$vfm} %%S%%{$vfs}";
					</script>
				{else}
					<script type="text/javascript">
						cd{$product.id}.DisplayFormat = "%%S%%{$vfs}";
					</script>
				{/if}
			{/if}
			<li class="ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if} {if $smarty.foreach.products.index % 2}alternate_item{else}item{/if} clearfix">
				{if isset($comparator_max_item) && $comparator_max_item && $compare==1}
					<div class="left_block">
						<p class="compare">
							<input type="checkbox" class="comparator" id="comparator_item_{$product.id_product}" value="comparator_item_{$product.id_product}" {if isset($compareProducts) && in_array($product.id_product, $compareProducts)}checked="checked"{/if} /> 
							<label for="comparator_item_{$product.id_product}">{l s='Select to compare'}</label>
						</p>
					</div>
				{/if}
			
				<div class="center_block">
					<a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, large_default)}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" /></a>
					{if $product.typesale==1 || $product.typesale==2 || $product.typesale==3}
						<span style="color:#{$flashtexttimeColor};font-style:italic;font-weight:bold;">{l s='Less than:' mod='dompromo'}</span><br />
					  <div id="vftimer{$product.id}" style="color:#{$flashtimeColor};font-weight:bold;" class="dptempsrestant">[vftimer{$product.id}]</div>
					{/if}
					<h3>{if $product.new == 1}<span class="new">{l s='new' mod='dompromo'}</span>{/if}<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.legend|escape:'htmlall':'UTF-8'}">{$product.name|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a></h3>
					<p class="product_desc"><a href="{$product.link|escape:'htmlall':'UTF-8'}">{$product.description_short|strip_tags:'UTF-8'|truncate:120:'...'}</a></p>
				</div>
			
				<div class="right_block">
					{if ($product.reduction != 0 && ($smarty.now|date_format:'%Y-%m-%d' <= $product.to|date_format:'%Y-%m-%d' && $smarty.now|date_format:'%Y-%m-%d' >= $product.from|date_format:'%Y-%m-%d') && $product.on_sale==1)}
						<span class="discount">{l s='On sale!' mod='dompromo'}</span>				
					{elseif  ($product.vfreduction != 0 && ($smarty.now|date_format:'%Y-%m-%d' <= $product.datefin|date_format:'%Y-%m-%d' && $smarty.now|date_format:'%Y-%m-%d' >= $product.datedebut|date_format:'%Y-%m-%d') && $product.typesale==1)}
						<span class="discount">{l s='Flash Sale' mod='dompromo'}</span>
					{elseif  ($product.vfreduction != 0 && ($smarty.now|date_format:'%Y-%m-%d' <= $product.datefin|date_format:'%Y-%m-%d' && $smarty.now|date_format:'%Y-%m-%d' >= $product.datedebut|date_format:'%Y-%m-%d') && $product.typesale==2)}
						<span class="discount">{l s='Coutant Price' mod='dompromo'}</span>
					{elseif  ($product.vfreduction != 0 && ($smarty.now|date_format:'%Y-%m-%d' <= $product.datefin|date_format:'%Y-%m-%d' && $smarty.now|date_format:'%Y-%m-%d' >= $product.datedebut|date_format:'%Y-%m-%d') && $product.typesale==3)}
						<span class="discount">{l s='Reduction of Stocks' mod='dompromo'}</span>
					{elseif ($product.reduction != 0 && ($smarty.now|date_format:'%Y-%m-%d' <= $product.to|date_format:'%Y-%m-%d' && $smarty.now|date_format:'%Y-%m-%d' >= $product.from|date_format:'%Y-%m-%d') && $product.on_sale==0)}
						<span class="discount">{l s='Specials' mod='dompromo'}</span>
					{/if}
					<br />
					<div class="dpoldprix">
						<span class="price-discount dpprixbarre">
							{if !$priceDisplay}{displayWtPrice p=$product.price_without_reduction}
							{else}{displayWtPrice p=$product.price_without_reduction}{/if}
						</span>&nbsp;&nbsp;
						{if $product.reduction_percent}<span class="reduction">(-{$product.reduction_percent|intval}%)</span>{/if}
					</div>

					{if !$priceDisplay || $priceDisplay == 2}<div class="dpnewprix"><span class="price" style="display: inline;">{convertPrice price=$product.price}</span>{if $priceDisplay == 2} {l s='+Tx' mod='dompromo'}{/if}{* <span class="exclusive"><span></span>{l s='Add to cart' mod='dompromo'}</span><br /> *}</div>{/if}
					{if $priceDisplay}<div class="dpnewprix"><span class="price" style="display: inline;">{convertPrice price=$product.price_tax_exc}</span>{if $priceDisplay == 2} {l s='-Tx' mod='dompromo'}{/if}</div>{/if}
					
					{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE && $product.id_product_attribute == 0}
						{if ($product.allow_oosp || $product.quantity > 0)}
							{if isset($static_token)}
								<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", true)}" title="{l s='Add to cart' mod='dompromo'}"><span></span>{l s='Add to cart' mod='dompromo'}</a>
							{else}
								<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add&amp;id_product={$product.id_product|intval}", true)} title="{l s='Add to cart' mod='dompromo'}"><span></span>{l s='Add to cart' mod='dompromo'}</a>
							{/if}			
						{else}
							<span class="exclusive"><span></span>{l s='Add to cart' mod='dompromo'}</span><br />
						{/if}
					{/if}
					<a class="button lnk_view" href="{$product.link|escape:'htmlall':'UTF-8'}" title="{l s='View'}">{l s='View' mod='dompromo'}</a>
				</div>
			 	<br class="clear"/>
			</li>
		{/foreach}
	</ul>
	
	<div class="content_sortPagiBar">
		{include file="$tpl_dir./pagination.tpl"}
	</div>
	<!-- /Products list -->

	{foreach from=$products item=product name=products}
		{if $product.typesale==1 || $product.typesale==2 || $product.typesale==3}
			<script type="text/javascript">
				<!--
				cd{$product.id}.Setup();
				//-->
			</script>
		{/if}
	{/foreach}

{else}
	<p class="warning">{l s='No Price drop .' mod='dompromo'}</p>
{/if}

<!-- /MODULE {$moduleinfo} -->
