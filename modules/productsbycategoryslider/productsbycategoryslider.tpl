{if count($categoryProducts) > 0 && $categoryProducts !== false}
	<h2 class="productscategory_h2">{l s='Discover also' mod='productsbycategoryslider'} :</h2>
	<div class="wrap">
		<ul id="slider-categoryslider" class="multiple" {if count($categoryProducts) > 5}style="width: {math equation="width * nbImages" width=107 nbImages=$categoryProducts|@count}px"{/if}>
			{foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
	 	 	<li style="width: 107px; float: left; list-style-type: none; list-style-position: initial; list-style-image: initial;">
	 	 		<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" class="product_image" {$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" title="{$categoryProduct.name|htmlspecialchars}">
	 	 			<img src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'medium_default')}" alt="{$categoryProduct.name|htmlspecialchars}">
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
</script>
{/if}