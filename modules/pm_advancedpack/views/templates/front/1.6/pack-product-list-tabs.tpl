{* Grid calculation *}
{assign var=nbTabs value=count($productsPackUnique)}
{if $product->description}
	{if $nbTabs % 2 == 0}{assign var=tabGridMd value="col-xs-12 col-sm-12 col-md-6"}{else}{assign var=tabGridMd value="col-xs-12 col-sm-12 col-md-4"}{/if}
{else}
	{if $nbTabs == 2}
		{assign var=tabGridMd value="col-xs-12 col-sm-12 col-md-6"}
	{else if $nbTabs is div by 3}
		{assign var=tabGridMd value="col-xs-12 col-sm-12 col-md-4"}
	{else if $nbTabs is div by 2}
		{assign var=tabGridMd value="col-xs-12 col-sm-12 col-md-3"}
	{else}
		{assign var=tabGridMd value="col-xs-12 col-sm-12 col-md-2"}
	{/if}
{/if}

<ul id="ap5-pack-product-tab-list" class="nav nav-tabs">
	{foreach from=$productsPackUnique item=productPack name=productPack_list}
		<li class="{$tabGridMd}{if $smarty.foreach.productPack_list.first} active{/if}">
			<a href="#pack-product-tab-{$productPack.id_product_pack|intval}" data-toggle="tab">
				<span class="ap5-pack-product-tab-name">{$productPack.productObj->name|escape:'html':'UTF-8'}</span>
				{if isset($productPack.gsrAverage) && !empty($productPack.gsrAverage)}
				<!-- Average rating from gsnippetsreviews -->
				<div id="productRating-{$productPack.productObj->id|intval}" class="ap5-gsnippetsreviews-average-container">{$productPack.gsrAverage}</div>
				{/if}
			</a>
		</li>
	{/foreach}
</ul>

<div id="ap5-pack-product-tabs-content" class="tab-content clearfix">
	{foreach from=$productsPackUnique item=productPack name=productPack_list}
		<div class="tab-pane fade{if $smarty.foreach.productPack_list.first} in active{/if}" id="pack-product-tab-{$productPack.id_product_pack|intval}">

			{if $packShowProductsShortDescription || $packShowProductsLongDescription}
			<div class="col-xs-12 col-sm-12 {if (!$packShowProductsLongDescription || !$product->description) && ($packShowProductsFeatures && isset($productPack.features) && $productPack.features)}col-md-6{else}col-md-12{/if}">
				<div class="rte">
					{if $packShowProductsShortDescription && $productPack.productObj->description_short}
						{$productPack.productObj->description_short}
					{/if}
					{if $packShowProductsLongDescription && $productPack.productObj->description}
						{if $packShowProductsShortDescription && $productPack.productObj->description_short}<hr />{/if}
						{$productPack.productObj->description}
					{/if}
				</div>
			</div>
			{/if}

			{if $packShowProductsFeatures && isset($productPack.features) && $productPack.features}
				<!-- Data sheet -->
				<div class="col-xs-12 col-sm-12 {if !$product->description}col-md-6{else}col-md-12{/if}">
					<section class="page-product-box">
						<h3 class="page-product-heading">{l s='Data sheet' mod='pm_advancedpack'}</h3>
						<table class="table-data-sheet">
							{foreach from=$productPack.features item=feature}
								<tr class="{cycle values="odd,even"}">
									{if isset($feature.value)}
										<td>{$feature.name|escape:'html':'UTF-8'}</td>
										<td>{$feature.value|escape:'html':'UTF-8'}</td>
									{/if}
								</tr>
							{/foreach}
						</table>
					</section>
				</div>
				<!--end Data sheet -->
			{/if}

			{if isset($productPack.gsrReviewsList) && !empty($productPack.gsrReviewsList)}
			<!-- Reviews from gsnippetsreviews -->
			<div class="col-xs-12 col-sm-12 col-md-12 clear ap5-gsnippetsreviews-reviews-container">{$productPack.gsrReviewsList}</div>
			{/if}

			{*
			<section class="page-product-box">
			{$productPack.HOOK_PRODUCT_TAB}
			{if isset($productPack.HOOK_PRODUCT_TAB_CONTENT) && $productPack.HOOK_PRODUCT_TAB_CONTENT}{$productPack.HOOK_PRODUCT_TAB_CONTENT}{/if}
			</section>
			*}
		</div>
	{/foreach}
</div>