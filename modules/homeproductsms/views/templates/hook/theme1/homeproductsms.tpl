<link href="{$theme_path}/views/templates/hook/theme1/theme1.css" rel="stylesheet" type="text/css">

<div class="main_hpm">

{if $gen_set_tab == 0}
<ul class="tabs" data-persist="true">
	{foreach from=$blk_home_prdcts item=title}
		{if $title.products_home}
			{if $title.en_dis_blk}
				{if $title.hdg_title_lang}
					<li><a  href="#row_id_{$title.id_ms}" class="title_hpm" >&nbsp;{$title.hdg_title_lang}&nbsp;</a></li>
					{else}
					<li><a  href="#row_id_{$title.id_ms}" class="title_hpm" >&nbsp;{$title.hdg_title}&nbsp;</a></li>
				{/if}
			{/if}
		{/if}
	{/foreach}
</ul>
{/if}


{foreach from=$blk_home_prdcts item=productd}
{if $productd.products_home}
	{if $gen_set_tab == 1}
		<style> .row_cont_hpm { display: inline-block;}</style>
		{if $productd.hdg_title_lang}
			<div class="title_hpm_each" >{$productd.hdg_title_lang}</div>
		{else}
			<div class="title_hpm_each" >{$productd.hdg_title}</div>
		{/if}
	{/if}

	{if $productd.en_dis_blk}
	<div class="row_cont_hpm">
	<div id="row_id_{$productd.id_ms|escape:'html':'UTF-8'}" >
	{if $gen_set_center_ms}<center>{/if}
	{foreach from=$productd.products_home item=product_hm}
		<div class="prd_cont_hpm">
			<div class="prd_ech_hpm">
  				<div class="img_container_hpm"><!-- image -->
					<a href="{$product_hm.link|escape:'html'}" title="{$product_hm.name|escape:'html':'UTF-8'}">
					<img src="{$link->getImageLink($product_hm.link_rewrite, $product_hm.id_image, 'home_default')|escape:'html'}"  alt="{$product_hm.name|escape:'html':'UTF-8'}"/>
					</a>
				</div><!-- end img_container_hpm -->

				<div class="name_hpm"><!-- description -->
				<a href="{$product_hm.link|escape:'html'}" title="{$product_hm.name|truncate:50:'...'|escape:'html':'UTF-8'}">{$product_hm.name|truncate:35:'...'|escape:'html':'UTF-8'}</a>
				</div><!-- end name_hpm -->

				{if $productd.dsp_prc}
				<div class="price_hpm"><!-- prices -->
					{if (!$PS_CATALOG_MODE AND ((isset($product_hm.show_price) && $product_hm.show_price) || (isset($product_hm.available_for_order) && $product_hm.available_for_order)))}
					<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" >
						{if isset($product_hm.show_price) && $product_hm.show_price && !isset($restricted_country_mode)}
							<span itemprop="price" class="prd_price_act_hpm" id="prd_price_act_hpm_{$product_hm.id_product}">
								{if !$priceDisplay}{convertPrice price=$product_hm.price}{else}{convertPrice price=$product_hm.price_tax_exc}{/if}
							</span>
							<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
							{if isset($product_hm.specific_prices) && $product_hm.specific_prices && isset($product_hm.specific_prices.reduction) && $product_hm.specific_prices.reduction > 0}
								<span class="prd_price_old_hpm" id="{$product_hm.id_product}_old_price">
									{displayWtPrice p=$product_hm.price_without_reduction}
								</span>
								{if $product_hm.specific_prices.reduction_type == 'percentage'}
									<span class="price_perc_reduce_hpm" id="price_perc_reduce_hpm_{$product_hm.id_product}" >-{$product_hm.specific_prices.reduction * 100}%</span>
								{/if}
							{/if}
						{/if}
					</div>
					{/if}
				</div><!-- price_hpm -->
				{/if}
				{if isset($product_hm.new) && $product_hm.new == 1}
							<a  href="{$product_hm.link|escape:'html':'UTF-8'}">
								<span class="new_img"><span class="new_txt">{l s='new' mod='homeproductsms'}</span></span>
							</a>
						{/if}
						{if isset($product_hm.on_sale) && $product_hm.on_sale && isset($product_hm.show_price) && $product_hm.show_price && !$PS_CATALOG_MODE}
							<a href="{$product_hm.link|escape:'html':'UTF-8'}">

								<span class="sale_img"><span class="sale_txt">{l s='Sale!' mod='homeproductsms'}</span></span>
							</a>
						{/if}

				<!--- addto cart and mode --->
				{if $productd.more_shw OR $productd.more_shw OR $productd.qck_view_shw} <!--- if add cart, more and quick view Start----->
				<div class="contain_inside">
				{if $productd.add_cart_shw}<!--- add to cart butoon start --->
						{if ($product_hm.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product_hm.available_for_order && !isset($restricted_country_mode) && $product_hm.minimal_quantity <= 1 && $product_hm.customizable != 2 && !$PS_CATALOG_MODE}
							{if ($product_hm.allow_oosp || $product_hm.quantity > 0)}
								{if isset($static_token)}
									<a class="box1 ajax_add_to_cart_button" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product_hm.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='homeproductsms'}" data-id-product="{$product_hm.id_product|intval}">
										<span>{l s='Add to cart' mod='homeproductsms'}</span>
									</a>
								{else}
									<a class="box1 ajax_add_to_cart_button" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product_hm.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='homeproductsms'}" data-id-product="{$product_hm.id_product|intval}">
										<span>{l s='Add to cart' mod='homeproductsms'}</span>
									</a>
								{/if}
							{else}
								<span class="button ajax_add_to_cart_button btn btn-default disabled">
									<span>{l s='Add to cart' mod='homeproductsms'}</span>
								</span>
							{/if}
						{/if}
				{/if}<!--- add to cart butoon end--->

				{if $productd.more_shw}<!--- more button start--->
						<a itemprop="url" class="box2" href="{$product_hm.link|escape:'html':'UTF-8'}" title="{l s='View' mod='homeproductsms'}">
							<span>{l s='More' mod='homeproductsms'}</span>
						</a>
				{/if} <!--- more button end-->

				{if $productd.qck_view_shw}<!--- quick view eye start-->
						<a itemprop="url" class="qckvw"  id="box3" href="{$product_hm.link|escape:'html':'UTF-8'}" rel="{$product_hm.link|escape:'html':'UTF-8'}" title="{l s='QUICK VIEW' mod='homeproductsms'}">
							<img src="{$theme_path}/views/templates/hook/theme1/img/quick_view47.png"  alt="{l s='QUICK VIEW' mod='homeproductsms'}"/>
						</a>

				{/if}<!--- quick view eye end -->
				</div>
				{/if}<!--- if add cart, more and quick view end ----->

				{if $productd.tim_cnt_dwn_shw} 	<!--tim_count -->
				<div class="time_cnt_dn_hpm">
				{if isset($product_hm.specific_prices) && $product_hm.specific_prices && isset($product_hm.specific_prices.reduction) && $product_hm.specific_prices.reduction > 0}
					{if $product_hm.specific_prices.to > 0}
							 	<div class="countdown">
      							<ul class="countdowsn_{$product_hm.id_product}">
      							<li>
            						<span class="days">00</span>
        						</li>
       							<li class="seperator">d :</li>
       							<li>
            						<span class="hours">00</span>
        						</li>
						        <li class="seperator">h :</li>
						        <li>
						            <span class="minutes">00</span>
						        </li>
						        <li class="seperator">m :</li>
						        <li>
						            <span class="seconds">00</span>
						        </li>
						        <li class="seperator">&nbsp;left</li>
								</ul>
								</div>

								<div class="cnt_hpm_over">
								<div class="count_{$product_hm.id_product}_over" style="display:none;">
								over
								</div>
								</div>

								<script class="source" type="text/javascript">
                                var  dateto =  '{$product_hm.specific_prices.to}';
                                var datemodded = dateto.split("-").join("\/"); 
							        $('.countdowsn_{$product_hm.id_product}').downCount({
							            date: datemodded,
							            offset: '{$time_offset_hours}'
							        }, function () {
							            $(".countdowsn_{$product_hm.id_product}").fadeOut(3000);
							            $(".count_{$product_hm.id_product}_over").fadeIn(3000).delay(3000).fadeOut(3000);
							            $("#prd_price_act_hpm_{$product_hm.id_product}").fadeOut(3000);
							            $("#price_perc_reduce_hpm_{$product_hm.id_product}").fadeOut(3000);
										$("#{$product_hm.id_product}_old_price").css("font-family", "helvetica,arial,sans-serif");
										$("#{$product_hm.id_product}_old_price").css("font-size","26px");
										$("#{$product_hm.id_product}_old_price").css("font-weight", "bold");
										$("#{$product_hm.id_product}_old_price").css("color", "#222");
										$("#{$product_hm.id_product}_old_price").css("text-decoration", "none");
										$("#{$product_hm.id_product}_old_price").css("margin-left", "0px");
							        });
							    </script>
					{/if}
				{/if}

				</div>
				{/if} <!-- time countdown end -->

			</div><!-- end prd_ech_hpm -->
		</div><!-- end prd_cont_hpm-->


   {/foreach}
   {if $gen_set_center_ms}</center>{/if}
   {if $productd.en_dis_adv}<!-- advertisment -->
   		<div class="advert_hpms">
			<center>
				{$productd.mkst_advert_blk|html_entity_decode:2:'UTF-8'}
			</center>
		</div>
   {/if}
   </div><!-- end id accrding to the product title-->
   </div><!-- end blk_cont_hpm -->


{/if}
{/if}
{/foreach}
</div><!-- end main_hpm -->
