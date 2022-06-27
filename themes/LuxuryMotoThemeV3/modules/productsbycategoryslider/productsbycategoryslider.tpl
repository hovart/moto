{if count($categoryProducts) > 0 && $categoryProducts !== false}
	<h2 class="productscategory_h2"><span>{l s='Discover also' mod='productsbycategoryslider'} </span></h2>


	<div  id="slider-categoryslider" class="tptncarousel">
		<div class="tptnslides row">
			{foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
				<div class="ajax_block_product item col-xs-12" itemscope itemtype="http://schema.org/Product">
                    <div class="item-content">
                        <div class="left-block">
                            <div class="product-image-container">
                                <a  href="{$categoryProduct.link|escape:'html':'UTF-8'}" title="{$categoryProduct.name|escape:'html':'UTF-8'}" itemprop="url" >
                                    <img class="replace-2x" src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$categoryProduct.name|htmlspecialchars}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />
                                </a>
                             {if isset($categoryProduct.new) && $categoryProduct.new == 1}<span class="new-box">{l s='New' mod='tptnprodtabs'}</span>{/if}
                            {if isset($categoryProduct.reduction) && $categoryProduct.reduction
                                }<span class="sale-box">
                                {strip}
                                    - {$categoryProduct.specific_prices.reduction*100}%
                                {/strip}
                                </span>{/if}
                            </div>
                            <div class="right-block">
                                <div class="manufacturerContainer">
                                    <img class="manufacturer" src="{$img_manu_dir}{$categoryProduct.id_manufacturer}.jpg" alt="{$categoryProduct.manufacturer_name|escape:'htmlall':'UTF-8'}" title="{$categoryProduct.manufacturer_name|escape:'htmlall':'UTF-8'}" />
                                </div>
                                <h5 itemprop="name">
                                    {if isset($categoryProduct.pack_quantity) && $categoryProduct.pack_quantity}{$categoryProduct.pack_quantity|intval|cat:' x '}{/if}
                                    <a class="product-name" href="{$categoryProduct.link|escape:'html':'UTF-8'}" title="{$categoryProduct.name|escape:'html':'UTF-8'}" itemprop="url" >
                                        {$categoryProduct.name|truncate:30:'...'|escape:'html':'UTF-8'}
                                    </a>
                                </h5>
                                {if (!$PS_CATALOG_MODE AND ((isset($categoryProduct.show_price) && $categoryProduct.show_price) || (isset($categoryProduct.available_for_order) && $categoryProduct.available_for_order)))}
                                    <div class="content_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        {if isset($categoryProduct.show_price) && $categoryProduct.show_price && !isset($restricted_country_mode)}
                                            <span itemprop="price" class="price product-price">
                                            {if !$priceDisplay}{convertPrice price=$categoryProduct.price}{else}{convertPrice price=$categoryProduct.price_tax_exc}{/if}
                                        </span>
                                                                    <meta itemprop="priceCurrency" content="{$currency->iso_code}" />
                                                                    {if isset($categoryProduct.specific_prices) && $categoryProduct.specific_prices && isset($categoryProduct.specific_prices.reduction) && $categoryProduct.specific_prices.reduction > 0}
                                                                        <span class="old-price product-price">
                                                {displayWtPrice p=$categoryProduct.price_without_reduction}
                                            </span>
                                                {/if}
                                            {/if}
                                        </div>
                                    {/if}
                            </div>
                         </div>
                      </div>
                </div>
			{/foreach}
		</div>
	</div>


    {*
	<div class="wrap">
		<ul id="slider-categoryslider" class="product_list grid row">
			{foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
	 	 	<li style=" float: left; list-style-type: none; list-style-position: initial; list-style-image: initial;">
	 	 		<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" class="product_image" {$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" title="{$categoryProduct.name|htmlspecialchars}">
				<img class="replace-2x" src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$categoryProduct.name|htmlspecialchars}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />

				{*<img src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'medium_default')}" alt="{$categoryProduct.name|htmlspecialchars}">*}
{*
</a>
<h5><a title="nom du produit" href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}">{$categoryProduct.name|truncate:20:'...'|escape:'htmlall':'UTF-8'}</a></h5>
<span class="price">{if $categoryProduct.reduction == 0}{convertPrice price=$categoryProduct.displayed_price}{else}<s>{convertPrice price=$categoryProduct.price_without_reduction}</s><br /><b>{convertPrice price=$categoryProduct.price}</b>{/if}</span>
</li>
{/foreach}
</ul>
</div>
{/if}
{if count($categoryProducts) > 5}
<script type="text/javascript">
$(document).ready(function(){
$('#slider-categoryslider').bxSliderCategorySlider({
displaySlideQty: 4,
moveSlideQty: 1
});
});
</script>*}
{/if}
