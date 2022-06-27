<div id="tptnprodtabs" class="container">
	<ul class="tabs_title">
		{if isset($show_featured_prod) AND $show_featured_prod}<li><a href="#featured">{l s='Featured' mod='tptnprodtabs'}</a></li>{/if}
		{if isset($show_new_prod) AND $show_new_prod}<li><a href="#new">{l s='New' mod='tptnprodtabs'}</a></li>{/if}
		{if isset($show_special_prod) AND $show_special_prod}<li><a href="#special">{l s='Specials' mod='tptnprodtabs'}</a></li>{/if}
		{if isset($show_best_prod) AND $show_best_prod}<li><a href="#best">{l s='Best sellers' mod='tptnprodtabs'}</a></li>{/if}
	</ul>
	
	<!-- Featured products -->
	{if isset($show_featured_prod) AND $show_featured_prod}
	<div id="featured" class="tptncarousel">
		{if isset($featured_products) AND $featured_products}
			<div class="tptnslides row">
			{foreach from=$featured_products item=product name=homeFeaturedProducts}
				{include file="$self/views/templates/hook/tptnprodtabs-list.tpl"}
			{/foreach}
			</div>
		{else}
			<p>{l s='Featured products are not availabe at this time.' mod='tptnprodtabs'}</p>
		{/if}
	</div>
	{/if}
	
	<!-- New products -->
	{if isset($show_new_prod) AND $show_new_prod}
	<div id="new" class="tptncarousel">
		{if isset($new_products) AND $new_products}
			<div class="tptnslides row">
			{foreach from=$new_products item=product name=homeNewProducts}
				{include file="$self/views/templates/hook/tptnprodtabs-list.tpl"}
			{/foreach}
			</div>
		{else}
			<p>{l s='New products are not availabe at this time.' mod='tptnprodtabs'}</p>
		{/if}
	</div>
	{/if}
	
	<!-- Special products -->
	{if isset($show_special_prod) AND $show_special_prod}
	<div id="special" class="tptncarousel">
		{if isset($special_products) AND $special_products}
			<div class="tptnslides row">
			{foreach from=$special_products item=product name=homeSpecialProducts}
				{include file="$self/views/templates/hook/tptnprodtabs-list.tpl"}
			{/foreach}
			</div>
		{else}
			<p>{l s='Specials are not available at this time.' mod='tptnprodtabs'}</p>
		{/if}
	</div>
	{/if}
	
	<!-- Best-seller products -->
	{if isset($show_best_prod) AND $show_best_prod}
	<div id="best" class="tptncarousel">
		{if isset($best_products) AND $best_products}
			<div class="tptnslides row">
			{foreach from=$best_products item=product name=homeBestProducts}
				{include file="$self/views/templates/hook/tptnprodtabs-list.tpl"}
			{/foreach}
			</div>
		{else}
			<p>{l s='Best sellers are not available at this time.' mod='tptnprodtabs'}</p>
		{/if}
	</div>
	{/if}
</div>